angular.module('badShows', []).controller('MainController', function($scope, $http) {

    // Add form hidden by default
    $scope.addForm = false

    // Show add form
    $scope.showAdd = function() {
        $scope.addForm = true
    }

    // Hide add form
    $scope.hideAdd = function() {
        $scope.addForm = false
    }

    // Show model
    $scope.show = {}

    // Fetch shows
    var getShows = function() {
        $http.get('/shows').success(function(data, status, headers, config) {
            $scope.shows = data
        })
    }

    // Posts the show object
    $scope.createShow = function() {
        $http.post('/shows', $scope.show)
            .success(function(data, status, headers, config) {
                // Reload shows
                getShows()
                // Hide the input form
                $scope.hideAdd()
            })
            .error(function(data, status, headers, config) {
                if (data.required) {
                    // Visible validation is only done in frontend right now
                    alert("Fill in all required fields")
                }
            })
    }

    // Delete show
    $scope.deleteShow = function(id) {
        $http.delete('/shows/' + id).success(function(data, status, headers, config) {
            //Reload Shows
            getShows()
        })
    }

    // Load shows
    getShows()
})