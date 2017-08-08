$(document).on('ready', function() {
    $('input[name=url]').focusin()
});

let is_private = false;
$('#is-private').on('click', function(e) {
    e.preventDefault()
    is_private = !is_private
    $('input[name=is_private]').val(is_private)
    $(this).toggleClass('is-active')
    $('#is_private_info').toggle()

});

$('select[name=protocol_select').on('change', function() {
    updateModel($('input[name=url]'), $(this), $('input[name=url_with_protocol'))
})

$('input[name=url]').on('keyup', function() {
    /* let selfValue = $(this).val()
    let protocolValue = $('select[name=protocol_select]').val() // http:// or https://
    let replaced = selfValue.replace('https://', '')
    replaced = replaced.replace('http://', '')

    $(this).val(replaced)

    $('input[name=url_with_protocol').val(protocolValue + selfValue) */
    updateModel($(this), $('select[name=protocol_select'), $('input[name=url_with_protocol'))
})

function updateModel(urlInput, protocolSelect, urlWithProtocolHidden) {
    let urlInputValue = urlInput.val()
    let protocolSelectValue = protocolSelect.val()
    let urlWithoutProtocol = urlInputValue.replace('https://', '')
    urlWithoutProtocol = urlWithoutProtocol.replace('http://', '')

    urlInput.val(urlWithoutProtocol)

    urlWithProtocolHidden.val(protocolSelectValue + urlInputValue)
}