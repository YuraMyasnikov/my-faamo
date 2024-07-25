transliterate = (
    function() {
        var
            rus = "щ   ш  ч  ц  ю  я  ё  ж  ъ  ы  э  а б в г д е з и й к л м н о п р с т у ф х ь ! \"".split(/ +/g),
            eng = "shh sh ch cz yu ya yo zh - y   e  a b v g d e z i j k l m n o p r s t u f x - - -".split(/ +/g)
            ;

        return function(text, engToRus) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/api/modules/infoblocks/translate?text='+text, false);
            xhr.send();
            if (xhr.status != 200) {
                // обработать ошибку
                console.log( xhr.statusText ); // пример вывода: 404: Not Found
            } else {
                var js = JSON.parse(xhr.responseText);
                if(js.status === 1) {
                    return js.text;
                }
            }

            //https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160707T084307Z.14a02db50bdfee00.2e4a4636518cdda70a5f46338eee84d2bbbf236c&text=%D1%81%D1%87%D0%B5%D1%82&lang=ru-en&format=plain

            var x;
            for(x = 0; x < rus.length; x++) {
                text = text.split(engToRus ? eng[x] : rus[x]).join(engToRus ? rus[x] : eng[x]);
                text = text.split(engToRus ? eng[x].toUpperCase() : rus[x].toUpperCase()).join(engToRus ? rus[x].toUpperCase() : eng[x].toUpperCase());
            }
            return text.replace(/\s/g, '_');
        }
    }
)();