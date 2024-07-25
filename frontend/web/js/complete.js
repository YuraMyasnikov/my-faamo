(function ()  {
    let count = 10;

    let timer = setInterval(function (){
        count--;

        let text = 'автоматичесуий переход будет через ' + count + ' секунд';
    
        $('#info-timer').html(text)

        if (count == 0) {
            clearInterval(timer)
            window.location.href = $('#info-timer').data('url')
        }
    },1000)
})();