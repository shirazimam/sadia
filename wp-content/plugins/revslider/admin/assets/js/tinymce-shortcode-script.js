function revslider_tiny_reset_all(){revslider_is_vc=!1,jQuery("#revslider-tiny-mce-settings-form").trigger("reset"),jQuery("#revslider-tiny-mce-dialog").show(),jQuery('#revslider-existing-slider option[value="-1"]').attr("selected","selected"),jQuery("#revslider-tiny-grid-settings-wrap").removeClass("notselectable"),jQuery("#rs-custom-elements-wrap").html(""),jQuery("#revslider-existing-slider option:selected").change(),jQuery("#rs-shortcode-select-wrapper li").each(function(){jQuery(this).removeClass("selected")})}function checkOpenRevDialogWindow(){setTimeout(function(){var a=jQuery("#revslider-tiny-mce-dialog");a.closest(".ui-dialog:visible")&&a.closest(".ui-dialog").find(".ui-dialog-titlebar-close").click()},100)}"undefined"==typeof rev_lang&&(rev_lang={},rev_lang.slider_revolution_shortcode_creator="Slider Revolution Shortcode Creator",rev_lang.shortcode_generator="Shortcode Generator",rev_lang.please_add_at_least_one_layer="Please add at least one Layer.",rev_lang.choose_image="Choose Image",rev_lang.shortcode_parsing_successfull="Shortcode parsing successfull. Items can be found in step 3",rev_lang.shortcode_could_not_be_correctly_parsed="Shortcode could not be parsed."),"undefined"!=typeof tinymce&&tinymce.PluginManager.add("revslider_sc_button",function(a,b){a.addButton("revslider_sc_button",{title:rev_lang.slider_revolution_shortcode_creator,icon:"icon dashicons-update",onclick:function(){opened_by_mce=!0,revslider_tiny_reset_all(),jQuery("#revslider-tiny-dialog-step-1").show(),jQuery("#revslider-tiny-dialog-step-1-5").hide(),a.windowManager.open({id:"revslider-tiny-mce-dialog",title:"",width:900,height:600,resizable:!1,wpDialog:!0},{plugin_url:b})}}),rs_open_editor=a}),jQuery(document).ready(function(){if("undefined"!=typeof QTags){var a=!0;if(void 0!==edButtons)for(var b in edButtons)if("slider-revolution"==edButtons[b].id){a=!1;break}a&&QTags.addButton("slider-revolution","Slider Revolution",function(){opened_by_mce=!1,revslider_tiny_reset_all(),jQuery("#revslider-tiny-dialog-step-1").show(),jQuery("#revslider-tiny-dialog-step-1-5").hide(),jQuery("#revslider-tiny-mce-dialog").dialog({modal:!0,title:"",width:900,height:600,resizable:!1,wpDialog:!0})})}});var opened_by_mce=!1,revslider_is_vc=!1,rs_cur_vc_obj=!1,rs_open_editor=!1;jQuery(document).ready(function(){function a(a){var b=jQuery('select[name="revslider-existing-slider"] option:selected').val();if("-1"!==b){var c="";void 0!==a&&"undefined"!=typeof a.order&&(c=' order="'+a.order.join()+'"');var d='[rev_slider alias="'+b+'"'+c+"][/rev_slider]";if(revslider_is_vc){var e={alias:b};void 0!==a&&"undefined"!=typeof a.order&&(e.order=a.order),rs_cur_vc_obj.model.save("params",e),jQuery("#revslider-tiny-mce-dialog").dialog("close")}else opened_by_mce?(tinyMCE.activeEditor.selection.setContent(d),rs_open_editor!==!1&&rs_open_editor.windowManager.close()):QTags.insertContent(d)}}jQuery("#rs-add-predefined-slider").hasClass("rs-clicklistener")||(jQuery("#rs-add-predefined-slider").addClass("rs-clicklistener"),jQuery("body").on("change","#revslider-existing-slider",function(){var a=jQuery("#revslider-existing-slider option:selected");"gallery"==a.data("slidertype")||"specific_posts"==a.data("slidertype")?jQuery("#rs-modify-predefined-slider").removeClass("nonclickable"):jQuery("#rs-modify-predefined-slider").addClass("nonclickable"),"-1"!=a.val()?jQuery("#rs-add-predefined-slider").removeClass("nonclickable"):jQuery("#rs-add-predefined-slider").addClass("nonclickable")}),jQuery("#revslider-existing-slider").change(),jQuery(document).ready(function(){jQuery(".rs-mod-slides-wrapper").sortable()}),jQuery("body").on("click","#rs-modify-predefined-slider",function(){var a=wp.template("rs-modify-slide-wrap"),b=jQuery("#revslider-existing-slider option:selected");if(("gallery"==b.data("slidertype")||"specific_posts"==b.data("slidertype"))&&(jQuery(".rs-mod-slides-wrapper").html(""),"undefined"!=typeof rev_sliders_info))for(var c in rev_sliders_info)if(c==b.data("sliderid")){for(var d in rev_sliders_info[c]){var e=a(rev_sliders_info[c][d]);jQuery(".rs-mod-slides-wrapper").append(e)}return jQuery("#revslider-tiny-dialog-step-1").hide(),jQuery("#revslider-tiny-dialog-step-1-5").show(),!0}}),jQuery("body").on("click","#rs-add-predefined-slider",function(){var b=jQuery(this).parents("form");return checkOpenRevDialogWindow(),!!validateForm(b)&&(a(),!1)}),jQuery("body").on("click","#rs-shortcode-select-wrapper li",function(){if(!jQuery(this).hasClass("rs-slider-modify-new-slider")){var b=(jQuery(this).data("sliderid"),jQuery(this).data("slideralias"));jQuery("#rs-shortcode-select-wrapper li").each(function(){jQuery(this).removeClass("selected")}),jQuery(this).addClass("selected"),jQuery('#revslider-existing-slider option[value="'+b+'"]').attr("selected","selected"),jQuery("#revslider-existing-slider option:selected").change()}})),jQuery("body").on("click","#revslider-add-custom-shortcode-modify",function(){var b=[],c=!1;jQuery(".rs-mod-slides-wrapper li").each(function(){var a=jQuery(this).find(".slide-published"),d=jQuery(this).attr("id").replace("slidelist_item_","");if(a.length>0&&!a.hasClass("pubclickable"))b.push(d);else{var e=jQuery(this).find(".slide-unpublished");if(e.length>0&&!e.hasClass("pubclickable"));else{var f=jQuery(this).find(".slide-hero-published");f.length>0&&!f.hasClass("pubclickable")&&0==c&&(b.push(d),c=!0)}}}),a({order:b}),checkOpenRevDialogWindow()}),jQuery("body").on("click",".rs-goto-step-1",function(){jQuery("#revslider-tiny-dialog-step-1").show(),jQuery("#revslider-tiny-dialog-step-1-5").hide()})});var revslider_create_by_predefined=!1;jQuery("body").on("change",'select[name="revslider-tiny-existing-settings"]',function(){var a=jQuery(this).val();"-1"!=a?(jQuery("#revslider-tiny-slider-settings-wrap").addClass("notselectable"),jQuery("#revslider-tiny-slider-settings-wrap").find("input, select, textarea").attr("disabled","disabled")):(jQuery("#revslider-tiny-slider-settings-wrap").removeClass("notselectable"),jQuery("#revslider-tiny-slider-settings-wrap").find("input, select, textarea").attr("disabled",!1))}),jQuery('select[name="revslider-tiny-existing-settings"]').change();