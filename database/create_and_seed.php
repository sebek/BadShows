<?php

$baseDir = dirname(__FILE__);

try {


    $db = new PDO("sqlite:$baseDir/badshows.sqlite3");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /**
     * Create cats table
     */
    $db->exec(
        "
          CREATE TABLE IF NOT EXISTS shows (
            id INTEGER PRIMARY KEY,
            date TEXT,
            start_time TEXT,
            lead_text TEXT,
            name TEXT,
            bline TEXT,
            synopsis TEXT,
            url TEXT
          )
         "
    );

    /**
     * Empty cats table before seed
     */
    $db->exec(
        "
            DELETE FROM shows
        "
    );

    /**
     * Seed initial data
     */
    $shows = file_get_contents("{$baseDir}/shows.json");
    $shows = json_decode($shows);

    $insert = "
      INSERT INTO shows (date, start_time, lead_text, name, bline, synopsis, url)
      VALUES (:date, :start_time, :lead_text, :name, :bline, :synopsis, :url)";

    $stmt = $db->prepare($insert);

    $stmt->bindParam(":date", $date);
    $stmt->bindParam(":start_time", $startTime);
    $stmt->bindParam(":lead_text", $leadText);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":bline", $bline);
    $stmt->bindParam(":synopsis", $synopsis);
    $stmt->bindParam(":url", $url);

    foreach ($shows as $show) {
        $date = $show->date;
        $startTime = $show->startTime;
        $leadText = $show->leadText;
        $name = $show->name;
        $bline = $show->bline;
        $synopsis = $show->synopsis;
        $url = $show->url;

        $stmt->execute();
    }

    echo "Shows watched and they're bad.";

} catch (PDOException $e) {
    echo $e->getMessage();
}
