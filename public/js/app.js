let is_private = false;
$('#is-private').on('click', function(e) {
    e.preventDefault()
    is_private = !is_private
    $('input[name=is_private]').val(is_private)
    $(this).toggleClass('is-active')
    $('#is_private_info').toggle()

});

$('select[name=protocol_select]').on('change', function() {
    updateModel($('input[name=url]'), $(this), $('input[name=url_with_protocol]'))
})

$('input[name=url]').on('keyup', function() {
    updateModel($(this), $('select[name=protocol_select]'), $('input[name=url_with_protocol]'))
})

$('#custom-alias-button').on('click', function() {
    $(this).hide()
    $('#custom-alias-prefix').removeClass('is-visible')
    $('#custom_alias_input').removeClass('is-visible')
    $('#custom_alias_info').removeClass('is-visible')
    $('input[name=custom_alias]').focus()
})

function updateModel(urlInput, protocolSelect, urlWithProtocolHidden) {
    let urlInputValue = urlInput.val()
    let protocolSelectValue = protocolSelect.val()
    let urlWithoutProtocol = urlInputValue.replace('https://', '')
    urlWithoutProtocol = urlWithoutProtocol.replace('http://', '')

    urlInput.val(urlWithoutProtocol)

    urlWithProtocolHidden.val(protocolSelectValue + urlInputValue)
}

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