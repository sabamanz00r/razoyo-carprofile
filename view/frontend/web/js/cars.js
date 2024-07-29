define([
    "jquery",
    "jquery/ui",
    "mage/cookies",
    "domReady!"
], function($){
    "use strict";
    function main(config) {
        const AjaxUrl = config.url;
        const isCar = config.is_car;
        $(document).ready(function (){
            $('#cars-block').show();
            sendAjax(AjaxUrl, '', 'get', isCar);
        });
        $(document).on('change',"[name='cars']",function(){
            $('#cars-block').show();
            sendAjax(AjaxUrl, $(this).val(), 'get');
        });
        $(document).on('click', '#save', function(ev){
            $('#cars-block').show();
            const carId = $(this).data('car-id');
            sendAjax(AjaxUrl, carId, 'save');
        });
        $(document).on('click', '#change-car', function(ev){
            if($(this).is(':checked')){
                $('#cars-block').show();
                sendAjax(AjaxUrl, '', 'get');
            } else {
                $('#cars-block').hide();
            }
        });
    };
    return main;
    function sendAjax(AjaxUrl, filter, action, isCar = false) {
        if (!isCar) {
            const formData = new FormData();
            formData.append('filter', filter);
            formData.append('action', action);
            formData.append('form_key', $.mage.cookies.get('form_key'));
            $.ajax({
                showLoader: true,
                url: AjaxUrl,
                data: formData,
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
            }).done(function (data) {
                if (data.action === 'get') {
                    $('#cars-block').html(data.result);
                    $('.car-detail').collapsible({
                        animate: {duration:10,easing:"easeOutCubic"},
                        icons: {"header": "plus", "activeHeader": "minus"},
                        collapsible: true,
                        active: false,
                        loadingClass: "loading",
                        ajaxContent: true,
                        ajaxUrlElement: '[data-ajax=true]'
                    });
                    const handleClassChange = (element) => {
                        const mutationConfig = { attributes: true, attributeFilter: ['class'] };
                        const callback = function(mutationsList, observer) {
                            for(let mutation of mutationsList) {
                                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                    if (element.classList.contains('loading')) {
                                        const contentDiv = element.querySelector('div[data-role="content"]');
                                        if (!contentDiv.innerHTML.trim()) {
                                            $(element).trigger('processStart');
                                        }
                                    } else {
                                        const contentDiv = element.querySelector('div[data-role="content"]');
                                        if (contentDiv.innerHTML.trim()) {
                                            $(element).trigger('processStop');
                                        }
                                    }
                                }
                            }
                        };
                        const observer = new MutationObserver(callback);
                        observer.observe(element, mutationConfig);
                    };
                    const elements = document.getElementsByClassName('car-detail');
                    for (let i = 0; i < elements.length; i++) {
                        handleClassChange(elements[i]);
                    }
                    return true;
                }
                if (data.action === 'save') {
                    window.location.href = data.result;
                }
            });
        }
    }
});
