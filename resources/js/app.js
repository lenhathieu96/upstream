require('../scss/app.scss');

/*
  Add custom scripts here
*/
global.$ = global.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
// require('highcharts');
require('parsleyjs');

$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

global.NProgress = require('nprogress');

let $body = $('body');

$body.removeClass('preload'); // To prevent CSS transition on page load

$body.on('change', 'select[data-fetch-child=true]', (e) => {
    let $parentOption = $(e.currentTarget);
    let targetSelector = $parentOption.attr('data-fetch-target');
    let fetchUrl = $parentOption.attr('data-fetch-url');

    let paramName = $parentOption.attr('data-fetch-param-name');

    if (paramName === undefined) {
        paramName = $parentOption.attr('name');
    }

    let $targetElement = $(targetSelector);
    let firstOptionHtml = $targetElement.find('option:first-child')[0].outerHTML;

    let submitData = {};
    submitData[paramName] = $parentOption.val();

    $.get(fetchUrl, submitData)
        .done(function(data) {
            let htmlOptions = Object.keys(data).map(function(key) {
                return `<option value="${key}">${data[key]}</option>`
            });
            $targetElement.html(firstOptionHtml + htmlOptions);
            $targetElement.trigger('change');
        });
});
