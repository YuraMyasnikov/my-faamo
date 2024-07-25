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

app.requires.push('ui.tinymce');
app.controller('IBlockContentCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {

    $scope.content = CONTENT ? CONTENT : [];
    $scope.name;
    $scope.multiDelete = MULTIDELETE ? MULTIDELETE : {};
    $scope.delete = {};

    var isChange = jQuery("#iblockCode").val().length === 0;

    jQuery('#iblockName').on('change keyup', function () {
        var _this = this;

        function rrun() {
            if (isChange) {
                jQuery("#iblockCode").val(transliterate($(_this).val()));
                document.getElementById('iBlockCodeLabel').setAttribute('class', document.getElementById('iblockName').value ? 'always-top' : '');
            }
        }

        window.clearTimeout(window.iblockTranslate);
        window.iblockTranslate = setTimeout(rrun, 300);
    });

    jQuery("#iblockCode").focus(function () {
        isChange = false;
    });
    $scope.tinymceOptions = {
        plugins: 'link image lists table code',
        menubar: false,
        statusbar: false,
        toolbar: [
            "bold italic underline forecolorpicker fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table | code | removeformat"
        ],
        paste_data_images: true,
        content_css: ['/css/wysiwyg.css'],
        images_upload_handler: function (blobInfo, success, failure) {
            var formData;
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('infoblock', $scope.name);
            formData.append('_csrf', $('meta[name=csrf-token]').attr('content'));
            jQuery.post({
                url: "/admin/modules/infoblocks/content/upload",
                processData: false,
                contentType: false,
                data: formData
        }, function (data) {
                var json = JSON.parse(data);
                success(json.location);
            });
        },
        width: 780
    };

    $scope.addContentValue = function (i) {
        if ($scope.content[i].multi)
            $scope.content[i].value.push({isNew: true, value: ''})
    }

    $scope.deleteContentValue = function (i, j) {
        if ($scope.content[i].multi) {
            if ($scope.content[i].value[j].id) {
                if ($scope.multiDelete[$scope.content[i].code] == undefined)
                    $scope.multiDelete[$scope.content[i].code] = [];
                $scope.multiDelete[$scope.content[i].code].push($scope.content[i].value[j].id);
            }

            $scope.content[i].value.splice(j, 1);
        }
    }

    $scope.changeIblickElementLink = function (i) {
        var childWindow = open('/admin/modules/infoblocks/content/iblock?code=' + $scope.content[i].propertyLinkCode + '&viewType=childWindow', window, 'resizable=no,width=800,height=600,left=0,top=0');
        childWindow.onload = function () {
            childWindow.iblockElementSelectCallback = function (id, title) {
                $scope.content[i].value = id;
                $scope.content[i].title = title;
                $scope.$apply();
                childWindow.close();
            }
        }
    }

    $scope.changeIblickMultiElementLink = function (i, j) {
        var childWindow = open('/admin/modules/infoblocks/content/iblock?code=' + $scope.content[i].propertyLinkCode + '&viewType=childWindow', window, 'resizable=no,width=800,height=600,left=0,top=0');
        childWindow.onload = function () {
            childWindow.iblockElementSelectCallback = function (id, title) {
                $scope.content[i].value[j].value = id;
                $scope.content[i].value[j].title = title;
                $scope.$apply();
                childWindow.close();
            }
        }
    }

    $scope.deleteVal = function (i) {
        $scope.content.forEach(function (element) {
            if (element.id === i && element.value) {
                $scope.delete[element.code] = element.value;
                element.value = '';
                $('[ng-class=content' + i + ']').remove();
            }
        });
    }
}]);
