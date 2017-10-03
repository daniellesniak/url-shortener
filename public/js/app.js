let is_private = false;
$('#is-private').on('click', function(e) {
    e.preventDefault()
    is_private = !is_private
    $('input[name=is_private]').val(is_private)
    $(this).toggleClass('is-active')
    $('#is_private_info').toggle()
});

$('#custom-alias-button').on('click', function() {
    $(this).hide()
    $('#custom-alias-prefix').removeClass('is-not-visible')
    $('#custom_alias_input').removeClass('is-not-visible')
    $('#custom_alias_info').removeClass('is-not-visible')
    $('input[name=custom_alias]').focus()
})

$('.refererAnchor').each(function () {
    let anchorText = $(this).text()
    let length = 25
    if(anchorText.length > length)
    {
        console.log(anchorText.length)
        console.log(anchorText.substr(0, length))
        $(this).text(anchorText.substr(0, length) + '[...]')
    }
})

$('#from-datepicker').datepicker({
    showButtonPanel: true,
    dateFormat: 'yy-mm-dd'
})
$('#to-datepicker').datepicker({
    showButtonPanel: true,
    dateFormat: 'yy-mm-dd'
})

function notificationGenerator(color, message)
{
    return '<div class="notification is-' + color + '">' + message + '</div>'
}

function pushNotification(html, delay, fadeOutDuration)
{
    let el = $(html)
    $('.notifications').append(el)
    el.delay(delay).fadeOut(fadeOutDuration)
}