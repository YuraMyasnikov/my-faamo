'use strict';

var app = angular.module('YiiCMS', []);
app.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = loadEvent.target.result;
                    });
                };
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);
app.service('notifyService', ['$compile', '$rootScope', function ($compile, $scope) {
    var notyScope = $scope.$new();
    notyScope.notifier = {};
    notyScope.notifier.notify = function (msg) {
        if (msg) {
            var el = document.getElementById('ntf');
            if (!el) {
                el = $compile(angular.element('<div class="notification-wrapper" ng-click="notifier.notify()"><div onclick="event.stopPropagation()" class="notification"><span id="ntf"></span><span class="notification-close-btn" ng-click="notifier.notify()">X</span></div></div>'))(notyScope);
                $('body').append(el);
                el = document.getElementById('ntf');
            }
            el.innerHTML = '';
            $(el).append($compile(msg.content)(notyScope));
            el = el.parentNode;
            el.parentNode.setAttribute('class', 'notification-wrapper active');
            if (msg.width) el.style.width = msg.width;
            if (msg.addClass) {
                el.setAttribute('class', 'notification showSweetAlert ' + msg.addClass)
            } else {
                el.setAttribute('class', 'notification showSweetAlert');
            }
        } else {
            var el = document.getElementById('ntf').parentNode;
            el.setAttribute('class', 'notification hideSweetAlert');
            el.parentNode.setAttribute('class', 'notification-wrapper hide');
            setTimeout(function () {
                el.style.width = '';
                el.parentNode.setAttribute('class', 'notification-wrapper');
                document.getElementById('ntf').innerHTML = '';
            }, 200);
            return notyScope.notifier.params;
        }
    };
    return notyScope.notifier;
}]);

app.directive('imageFile', [function () {
    return {
        scope: {},
        link: function (scope, element, attr) {
            var wrapper = document.createElement("label");
            wrapper.className = 'directive-upload-file';
            wrapper.id = "w" + attr.id;
            wrapper.style.backgroundImage = 'url(' + attr.preview + ')';
            var node = document.getElementById(attr.id);
            node.parentNode.insertBefore(wrapper, node);
            node.parentNode.removeChild(node);
            wrapper.appendChild(node);

            node.addEventListener('change', function (e) {
                var input = e.target;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        wrapper.style.backgroundImage = 'url(' + e.target.result + ')'
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
    }
}]);

app.controller('IBlockTypeCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    $scope.properties = PHPCONFIG ? PHPCONFIG : [];
    $scope.deleteProps = [];
    $scope.validatorsWnd = false;
    $scope.relations = [];
    $scope.types = [
        {id: 1, name: 'Строка'},
        {id: 2, name: 'Текст'},
        {id: 3, name: 'Картинка'},
        {id: 4, name: 'Привязка к элементам'},
        {id: 5, name: 'Чекбокс'},
        {id: 6, name: 'Дата'},
        {id: 7, name: 'Дата и время'},
        {id: 8, name: 'Файл'}
    ];

    $scope.relationTypes = [
        {id: 1, name: 'Один ко многим'},
        {id: 2, name: 'Многие к одному'},
    ];

    $scope.validatorBaseTypes = [
        {code: 'integer', name: 'Целое число'},
        {code: 'float', name: 'Число с плавающей точкой'},
        {code: 'date', name: 'Дата'},
        {code: 'time', name: 'Время'},
        {code: 'datetime', name: 'Дата и время'},
        {code: 'email', name: 'Электронная почта'},
        {code: 'url', name: 'Адрес сайта'}
    ];

    $scope.addProperty = function () {
        $scope.properties.push({name: '', new_code: '', type: 1})
    };

    $scope.canSave = function () {
        var isCan = true, types = {};

        angular.forEach($scope.properties, function (v, k) {
            if (v.new_code.replace(/(^\s+|\s+$)/g, '').length === 0 || v.name.replace(/(^\s+|\s+$)/g, '').length == 0)
                isCan = false;

            if (types[v.new_code] === void 0) {
                types[v.new_code] = 1;
                if(v.id > 0)
                    types[v.code] = 1;
            }
            else isCan = false;
        });
        return isCan;
    }

    $scope.getCode = function(p) {
        return p.id > 0 ? p.code : p.new_code;
    }

    $scope.deleteProp = function(i) {
        if(!confirm('Вы действительно хотите удалить опцию '+$scope.properties[i].name+'?')) return false;
        if($scope.properties[i].id > 0) $scope.deleteProps.push($scope.properties[i].code);
        $scope.properties.splice(i,1);
    }

    $scope.getRelationProperties = function(i){
        if(parseInt(i) > 0) {
            var relation = $filter('filter')($scope.relations, {id: i}, true)[0];
            if(relation !== undefined)
                return relation.properties;
        }
        return [];
    }

    $scope.updateProp = function (i) {
        $scope.validatorsWnd = true;
        $scope.editablePropertyId = i;
        $scope.editableProperty = JSON.parse(JSON.stringify($scope.properties[i]));
        if($scope.editableProperty.type === 4) {
            $http.get('/api/modules/infoblocks').then(function(result) {
                $scope.relations = result.data;
                if($scope.editableProperty.relation === undefined)
                    $scope.editableProperty.relation = {type: 1};
            })
        } else delete $scope.editableProperty.relation;

        if($scope.editableProperty.validators.type === undefined) {
            $scope.editableProperty.validators.type = {};
            $scope.editableProperty.validators.type.param1=$scope.validatorBaseTypes[0].code;
        }
    }

    $scope.saveValidators = function() {
        $scope.properties[$scope.editablePropertyId] = $scope.editableProperty;
        $scope.validatorsWnd = false;
    }

    $scope.getModel = function (p, name) {
        return p.id > 0 ? p['new_' + name] : p[name];
    }
}]);