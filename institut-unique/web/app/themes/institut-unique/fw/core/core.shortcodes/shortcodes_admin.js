// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	JACQUELINE_STORAGE['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	JACQUELINE_STORAGE['shortcodes_popup'] = null;		// Popup with current shortcode settings
	JACQUELINE_STORAGE['shortcodes_current_idx'] = '';	// Current shortcode's index
	JACQUELINE_STORAGE['shortcodes_tab_clone_tab'] = '<li id="jacqueline_shortcodes_tab_{id}" data-id="{id}"><a href="#jacqueline_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	JACQUELINE_STORAGE['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		JACQUELINE_STORAGE['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (JACQUELINE_STORAGE['shortcodes_current_idx'] == '') return;
		var sc = jacqueline_clone_object(JACQUELINE_SHORTCODES_DATA[JACQUELINE_STORAGE['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!jacqueline_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ jacqueline_shortcodes_prepare_layout(sc);


		// Show Dialog popup
		JACQUELINE_STORAGE['shortcodes_popup'] = jacqueline_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				jacqueline_options_init(popup);
				popup.find('.jacqueline_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = jacqueline_shortcodes_get_code(JACQUELINE_STORAGE['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		JACQUELINE_STORAGE['shortcodes_popup'].find('.jacqueline_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				jacqueline_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		JACQUELINE_STORAGE['shortcodes_popup'].find('.jacqueline_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.jacqueline_shortcodes_tab').find('.jacqueline_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function jacqueline_shortcodes_get_code(popup) {
	JACQUELINE_STORAGE['sc_custom'] = '';
	
	var sc_name = JACQUELINE_STORAGE['shortcodes_current_idx'];
	var sc = JACQUELINE_SHORTCODES_DATA[sc_name];
	var tabs = popup.find('.jacqueline_shortcodes_tab > ul > li');
	var decor = !jacqueline_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + jacqueline_shortcodes_get_code_from_tab(popup.find('#jacqueline_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (jacqueline_isset(sc.children)) {
		if (JACQUELINE_STORAGE['sc_custom']!='no') {
			var decor2 = !jacqueline_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#jacqueline_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + jacqueline_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (jacqueline_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (jacqueline_isset(sc.container) && sc.container && popup.find('#jacqueline_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#jacqueline_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (jacqueline_isset(sc.container) && sc.container || jacqueline_isset(sc.children))
		rez += 
			(jacqueline_isset(sc.children) && decor && JACQUELINE_STORAGE['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function jacqueline_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.jacqueline_options_field').hasClass('jacqueline_options_no_use') && param.substr(0, 1)!='_' && !jacqueline_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+jacqueline_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			JACQUELINE_STORAGE['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (JACQUELINE_STORAGE['sc_custom']!='no' && mainTab) {
		var sc = JACQUELINE_SHORTCODES_DATA[JACQUELINE_STORAGE['shortcodes_current_idx']];
		var sc_name = JACQUELINE_STORAGE['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team' || sc_name == 'trx_price_table') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}


// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function jacqueline_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!jacqueline_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="jacqueline_shortcodes_body jacqueline_options_body"><form>';
	output += jacqueline_shortcodes_show_tabs(field);
	output += jacqueline_shortcodes_show_field(field, 0);
	if (!jacqueline_empty(field.children)) {
		JACQUELINE_STORAGE['shortcodes_tab_clone_content'] = jacqueline_shortcodes_show_field(field.children, 1);
		output += JACQUELINE_STORAGE['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function jacqueline_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="jacqueline_shortcodes_tab jacqueline_options_container jacqueline_options_tab">'
		+ '<ul>'
		+ JACQUELINE_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (jacqueline_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += JACQUELINE_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += JACQUELINE_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function jacqueline_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( JACQUELINE_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', JACQUELINE_SHORTCODES_DATA[JACQUELINE_STORAGE['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.jacqueline_shortcodes_tab').append(JACQUELINE_STORAGE['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.jacqueline_shortcodes_tab').tabs('refresh');
	jacqueline_options_init(tab.parents('.jacqueline_shortcodes_tab').find('.jacqueline_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function jacqueline_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="jacqueline_shortcodes_' + tab_id + '_content" class="jacqueline_options_content jacqueline_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = jacqueline_isset(param['divider']) && param['divider'] ? ' jacqueline_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!jacqueline_isset(param['before'])) param['before'] = {};
				param['before'] = jacqueline_merge_objects({
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'sizes': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					}, param['before']);
				if (!jacqueline_isset(param['after'])) param['after'] = {};
				param['after'] = jacqueline_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					}, param['after']);
			}
			if (param['type']=='color' && (JACQUELINE_STORAGE['shortcodes_cp']=='tiny' || (jacqueline_isset(param['style']) && param['style']!='wp'))) {
				if (!jacqueline_isset(param['after'])) param['after'] = {};
				param['after'] = jacqueline_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'color_reset'
					}, param['after']);
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (jacqueline_isset(param['before'])) {
				rez = jacqueline_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (jacqueline_isset(param['after'])) {
				rez = jacqueline_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (jacqueline_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (jacqueline_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' jacqueline_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="jacqueline_options_field'
					+ ' jacqueline_options_field_' + (jacqueline_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (jacqueline_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' jacqueline_options_field_text'  : '')
					+ (param['type']=='socials' && !jacqueline_empty(param['style']) && param['style']=='images' ? ' jacqueline_options_field_images'  : '')
					+ (param['type']=='socials' && (jacqueline_empty(param['style']) || param['style']=='icons') ? ' jacqueline_options_field_icons'  : '')
					+ (jacqueline_isset(param['dir']) && param['dir']=='vertical' ? ' jacqueline_options_vertical' : '')
					+ (!jacqueline_empty(param['multiple']) ? ' jacqueline_options_multiple' : '')
					+ (jacqueline_isset(param['size']) ? ' jacqueline_options_size_'+param['size'] : '')
					+ (jacqueline_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="jacqueline_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="jacqueline_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!jacqueline_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="jacqueline_options_input jacqueline_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + jacqueline_shortcodes_prepare_value(param['value']) + '" data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (jacqueline_isset(param['style']) && param['style']=='inline') {
					output += '<div class="jacqueline_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!jacqueline_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!jacqueline_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!jacqueline_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
							+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="jacqueline_options_input jacqueline_options_input_date' + (!jacqueline_empty(param['mask']) ? ' jacqueline_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!jacqueline_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!jacqueline_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="jacqueline_options_input jacqueline_options_input_text' + (!jacqueline_empty(param['mask']) ? ' jacqueline_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
					+ (!jacqueline_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = jacqueline_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = jacqueline_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="jacqueline_options_input jacqueline_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="jacqueline_options_input jacqueline_options_input_spinner' + (!jacqueline_empty(param['mask']) ? ' jacqueline_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"' 
					+ (!jacqueline_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (jacqueline_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (jacqueline_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!jacqueline_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="jacqueline_options_arrows"><span class="jacqueline_options_arrow_up iconadmin-up-dir"></span><span class="jacqueline_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(JACQUELINE_STORAGE['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (jacqueline_empty(tags[i])) continue;
						output += '<span class="jacqueline_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="jacqueline_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="jacqueline_options_input jacqueline_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!jacqueline_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!jacqueline_empty(param['disabled']) ? 'jacqueline_options_state_disabled' : '') + (param['value']=='true' ? ' jacqueline_options_state_checked' : '') + '"><span class="jacqueline_options_input_checkbox_image iconadmin-check"></span>' + (!jacqueline_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="jacqueline_options_radioitem"><input class="jacqueline_options_input jacqueline_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + jacqueline_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="jacqueline_options_state_checked"' : '') + '><span class="jacqueline_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(jacqueline_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="jacqueline_options_switch' + (param['value']==opt[1]['key'] ? ' jacqueline_options_state_off' : '') + '"><span class="jacqueline_options_switch_inner iconadmin-circle"><span class="jacqueline_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="jacqueline_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="jacqueline_options_input jacqueline_options_input_text jacqueline_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
					+ (!jacqueline_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!jacqueline_empty(param['value'])) {
					var fname = jacqueline_get_file_name(param['value']);
					var fext  = jacqueline_get_file_ext(param['value']);
					output += '<a class="jacqueline_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && jacqueline_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = jacqueline_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="jacqueline_options_input_range" data-step="'+(!jacqueline_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="jacqueline_options_range_scale"><span class="jacqueline_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(JACQUELINE_STORAGE['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(JACQUELINE_STORAGE['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="jacqueline_options_range_slider"><span class="jacqueline_options_range_slider_value">' + sliders[i] + '</span><span class="jacqueline_options_range_slider_button"></span></span>';
				}
				output += '<span class="jacqueline_options_range_min">' + param['min'] + '</span><span class="jacqueline_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="jacqueline_options_listitem'
						+ (jacqueline_in_list(param['value'], key, JACQUELINE_STORAGE['shortcodes_delimiter']) ? ' jacqueline_options_state_checked' : '') + '"'
						+ ' data-value="' + jacqueline_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!jacqueline_isset(param['options']) && !jacqueline_empty(param['from']) && !jacqueline_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!jacqueline_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = jacqueline_shortcodes_menu_list(param);
				if (jacqueline_empty(param['style']) || param['style']=='select') {
					output += '<input class="jacqueline_options_input jacqueline_options_input_select" type="text" value="' + jacqueline_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!jacqueline_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
						+ ' />'
						+ '<span class="jacqueline_options_field_after jacqueline_options_with_action iconadmin-down-open" onchange="jacqueline_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = jacqueline_shortcodes_menu_list(param);
				if (jacqueline_empty(param['style']) || param['style']=='select') {
					output += '<div class="jacqueline_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = jacqueline_shortcodes_menu_list(param);
				if (jacqueline_empty(param['style']) || param['style']=='select') {
					output += '<div class="jacqueline_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
						+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!jacqueline_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = jacqueline_shortcodes_menu_list(param);
				if (jacqueline_empty(param['style']) || param['style']=='icons') {
					rez2 = jacqueline_shortcodes_action_button({
						'action': jacqueline_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (jacqueline_empty(param['style']) || param['style']=='icons') && !jacqueline_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="jacqueline_options_input jacqueline_options_input_text jacqueline_options_input_socials' 
					+ (!jacqueline_empty(param['mask']) ? ' jacqueline_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + jacqueline_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!jacqueline_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!jacqueline_empty(param['style']) && param['style']=='images') {
					output += '<div class="jacqueline_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + jacqueline_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				var cp_style = jacqueline_isset(param['style']) ? param['style'] : JACQUELINE_STORAGE['shortcodes_cp'];
				output += '<input class="jacqueline_options_input jacqueline_options_input_color jacqueline_options_input_color_'+cp_style +'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' data-param="' + jacqueline_shortcodes_prepare_value(param_num) + '"'
					+ ' type="text"'
					+ ' value="' + jacqueline_shortcodes_prepare_value(param['value']) + '"'
					+ (!jacqueline_empty(param['action']) ? ' onchange="jacqueline_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before;
				if (cp_style=='custom')
					output += '<span class="jacqueline_options_input_colorpicker iColorPicker"></span>';
				else if (cp_style=='tiny')
					output += after;
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!jacqueline_empty(param['desc']))
					output += '<div class="jacqueline_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function jacqueline_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="jacqueline_options_input_menu ' + (jacqueline_empty(field['style']) ? '' : ' jacqueline_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (jacqueline_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (jacqueline_in_list(field['value'], key, JACQUELINE_STORAGE['shortcodes_delimiter'])) {
			caption = value;
			selected = ' jacqueline_options_state_checked';
		}
		list += '<span class="jacqueline_options_menuitem' 
			+ selected 
			+ '" data-value="' + jacqueline_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (jacqueline_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="jacqueline_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="jacqueline_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function jacqueline_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' jacqueline_options_button_' + type + (jacqueline_empty(data['title']) ? ' jacqueline_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'jacqueline_options_input_button'  : 'jacqueline_options_field_'+type)
				+ (!jacqueline_empty(data['action']) ? ' jacqueline_options_with_action' : '')
				+ (!jacqueline_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!jacqueline_empty(data['icon']) && !jacqueline_empty(data['title']) ? ' title="'+jacqueline_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!jacqueline_empty(data['action']) ? ' onclick="jacqueline_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!jacqueline_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!jacqueline_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!jacqueline_empty(data['sizes']) ? ' data-sizes="'+data['sizes']+'"' : '')
				+ (!jacqueline_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!jacqueline_empty(data['captions']) && !jacqueline_empty(data['captions']['choose']) ? ' data-caption-choose="'+jacqueline_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!jacqueline_empty(data['captions']) && !jacqueline_empty(data['captions']['update']) ? ' data-caption-update="'+jacqueline_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (jacqueline_empty(data['icon']) && !jacqueline_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function jacqueline_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
