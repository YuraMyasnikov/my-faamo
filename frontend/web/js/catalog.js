(function() {
    const DEFAULT_VALUES_FOR_FILTERS = {
        'sort': '',
        // 'sort': 'date',
        // 'min-price': 0,
        // 'max-price': 9999999,
        // 'prices': 0,
        // 'prices1': 9999999
    };

    const EXTRA_FILTERS = [
        // 'prices',
        // 'prices1'
    ];

    const NON_OPTIONS_FILTERS = [
        'sort',
        'filter_category',
        // 'min-price',
        // 'max-price',
        // 'prices',
        // 'prices1'
    ];

    const urlCreater = function(currentUrl, serializedFormData, category) {
        let map = new Map();
        serializedFormData.split('%5B%5D').join('').split('&').forEach((element) => {
            if(!element.length) {
                return;
            }
            let arr = element.split('=')
            if (map.has(arr[0])) {
                let value = map.get(arr[0]) + ',' + arr[1]
                map.set(arr[0], value)
            } else {
                map.set(arr[0], arr[1])
            }
        });
        let sort = currentUrl.searchParams.get('sort');
        if(sort) {
            map.set('sort', sort);
        }

        let nonOptionFilters = new Map();
        let newMap2 = new Map();

        map.forEach((element, key) => {
            if (NON_OPTIONS_FILTERS.includes(key)) {

                if (
                    (DEFAULT_VALUES_FOR_FILTERS[key] !== undefined && DEFAULT_VALUES_FOR_FILTERS[key] == element)
                    || EXTRA_FILTERS.includes(key)
                    || (key === 'filter_category' && element === category)
                ) {
                    return;
                }
                nonOptionFilters.set(key, element);
                return
            }

            let a = element.split(',');

            a.sort(function(a, b){
                let sortA = $('#' + a).data('sort');
                let sortB = $('#' + b).data('sort')

                return sortA - sortB;
            })

            newMap2.set(key, a);

        });

        newMap2 = new Map([...newMap2.entries()].sort(function(a, b){
            let sortA = $('#' + a[0]).data('catalog-sort');
            let sortB = $('#' + b[0]).data('catalog-sort');

            return sortA - sortB;
        }));        


        let limit = 2;
        let chpuFilters = [];
        newMap2.forEach(function (element, key) {
            option = $('#' + key);
            if (chpuFilters.length < limit && option.data('friendly') === 1) {
                chpuFilters.push(element[0]);
                element.splice(0, 1);

                if (element.length < 1) {
                    newMap2.delete(key)
                }
            }
        })        

        let resultMap = new Map([...newMap2, ...nonOptionFilters]);        
        
        let validUrl = ''

        resultMap.forEach((element, key) => {
            validUrl = validUrl.length !== 0 ? validUrl + '&' + key + '=' + element : validUrl + key + '=' + element
        })
        console.log(resultMap);
        
        let cityPrefix = window.location.pathname.startsWith('/msk') ? 1 : 0;
        let basePath = window.location.pathname.split('/', 3+cityPrefix).join('/');

        if (!!chpuFilters.join('-')) {
            basePath += '/' + chpuFilters.join('-');
        }

        validUrl = basePath + '?' + validUrl;

        return validUrl;
    };

    const filter = function(currentUrl, serializedFormData) {
        let cityPrefix = window.location.pathname.startsWith('/msk') ? 1 : 0;
        let currentCategory = window.location.pathname.split('/', 3)[2+cityPrefix];
        let validUrl = urlCreater(currentUrl, serializedFormData, currentCategory);

        history.pushState(null, null, validUrl);

        $.pjax.reload({
            container: '#pjax-container-catalog',
            async: false
        });
    };

    const serializeData = () => {
        return $(document).find('form.filters-panel').serialize();
    };

    $(document).on('click', '.sort-variant', function(event) {
        $(document).find('input[name="sort"]').val($(this).data('sort'));

        $(document).trigger('change-sotr', [$(this).data('sort')]);
    });

    $(document).on('change-sotr', function(event, sort) {

        const url = new URL(window.location);
        url.searchParams.set('sort', sort);
        window.history.pushState({}, '', url);

        let currentUrl = new URL(window.location);
        let serializedFormData = serializeData();
        setTimeout(function() {
            filter(currentUrl, serializedFormData);
        }, 10);
    });

    $(document).on('click', '.popup-bx-right-clear', function() {
        $(document).find('.filter-checkbox').removeAttr('checked');
        let currentUrl = new URL(window.location);
        let serializedFormData = serializeData();

        setTimeout(function() {
            filter(currentUrl, serializedFormData);
        }, 10);
    });

    $(document).on('change', '.filter-checkbox', function(event) {
        let cb = $(event.target);
        let is = cb.is(':checked')
        let relatedCb = cb.data('related');
        if(is) {
            $('#'+relatedCb).attr('checked', 'checked');    
        } else {
            $('#'+relatedCb).removeAttr('checked');    
        }
        let currentUrl = new URL(window.location);
        let serializedFormData = serializeData();
        setTimeout(function() {
            filter(currentUrl, serializedFormData);
        }, 10);
    });
    
    $(document).on('submit', '.filters-panel', function (event) {
        event.preventDefault();

        let currentUrl = new URL(window.location);
        let serializedFormData = $(this).serialize();
        // console.log(serializedFormData); return;
        let currentCategory = window.location.pathname.split('/', 3)[1];
        let validUrl = urlCreater(currentUrl, serializedFormData, currentCategory);

        history.pushState(null, null, validUrl);

        $.pjax.reload({
            container: '#pjax-container-catalog',
            async: false
        });
    });

    $('.fsize, .sootv, .color, .brand, .textile').click(function() {
        $(this).find('.filter').toggleClass('active');
        $(this).find('.dropdown-menu').toggleClass('active').toggle();
    });
})();