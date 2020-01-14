'use strict';
/**
 * Displays a list of meta information
 *
 * @author    Pierre Allard <pierre.allard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'pim/product-edit-form/template/image-manipulation',
        'pim/fetcher-registry',
        'pim/media-field',
        'jquery'
    ],
    function (_, __, BaseForm, template,FetcherRegistry,mediafield,$) {
        return BaseForm.extend({
            template: _.template(template),

            config: {},
            events:{
                'change .tab-content input[type="file"]':'ManipulateImage'
            },
            /**
             * {@inheritdoc}
             */
            initialize: function (config) {
                this.config = config.config;
                
                return BaseForm.prototype.initialize.apply(this, arguments);
            },
            configure: function () {
                console.log("this get root",this.getRoot());
                this.listenTo(this.getRoot(), 'pim_enrich:form:field:extension:add', this.ManipulateImage);
                return BaseForm.prototype.configure.apply(this, arguments);
            },

            ManipulateImage:function(event){
                console.log("event from image-manipulation");
                console.log("event",event);
                if(event.field.options.code == 'image_1'){
                    var originalFilename=event.field.model.attributes.values[0].data.originalFilename;
                    var filePath=event.field.model.attributes.values[0].data.filePath;
                    if(!filePath || !originalFilename){
                        return false;
                    }
                    var sPageURL = window.location.href;
                    console.log(sPageURL,"sPageURL");
                    console.log(window.location,"window.location");
                    var sURLVariables = sPageURL.split('/');
                    console.log(sURLVariables,"sURLVariables");
                    var data={
                        "productId":sURLVariables[sURLVariables.length-1],
                        "originalFileName":originalFilename,
                        "filePath":filePath
                    }
                    var payload ={
                      url: "sitation/imagemanipulation",
                      type: "POST",
                      data: data,
                      method: "POST",
                      success:this.responseData
                    };
                console.log(payload,"request");
                var request=$.ajax(payload);
                }
                
            },
            responseData:function(response){
                console.log("response",response);
            },

            /**
             * {@inheritdoc}
             */
            render: function () {
                this.$el.empty().append(this.template());
                return this;
            }

        });
    }
);
