<?php
$fontOptions = array (

    array( "name" => $fontUploaderName." Options",
        "type" => "title"),
   
    array( "name" => "Fonts",
        "type" => "section"),
    array( "type" => "open"),

	 array( "name" => "Headers",
		"desc" => "Font for header elements, such as h1, h2.",
		"id" => $sn."_header_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),
	 array( "name" => "Lists",
		"desc" => "Font for list items",
		"id" => $sn."_lists_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),
	 array( "name" => "Main Body",
		"desc" => "Font for the main body text of the website",
		"id" => $sn."_body_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),
		   
    array( "type" => "close"),
    
    array( "name" => "Advanced - Custom Elements",
        "type" => "section"),
    array( "type" => "open"), 
    
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_custom_one",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_custom_one_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 

	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_custom_two",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_custom_two_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_custom_three",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_custom_three_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_custom_four",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_custom_four_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_custom_five",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_custom_five_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),

		   
    array( "type" => "close"),

    array( "name" => "Google Fonts",
        "type" => "section"),
    array( "type" => "open"), 

	 array( "name" => "Google Font URLs",
		"desc" => "Enter the URLs to your Google fonts with each link on a new line.<br />The links should look like: &lt;link&gt; . . . &lt;/link&gt;",
		"id" => $sn."_google_font_urls",
		"class" => "google_font_url",
		"type" => "textarea"),
	 array( "name" => "Google Font Name - Headers",
		"desc" => "Enter the name of the font. For example, if Google tells you <em>font-family: <strong>Tangerine</strong></em>, you type <em>Tangerine</em>",
		"id" => $sn."_google_header_font_name",
		"type" => "text"),
		
	 array( "name" => "Google Font Name - Body",
		"desc" => "Enter the name of the font. For example, if Google tells you <em>font-family: <strong>Lobster</strong></em>, you type <em>Lobster</em>",
		"id" => $sn."_google_body_font_name",
		"type" => "text"),
		
	 array( "name" => "Google Font Name - Lists",
		"desc" => "Enter the name of the font. For example, if Google tells you <em>font-family: <strong>Reanie Beanie</strong></em>, you type <em>Reanie Beanie</em>",
		"id" => $sn."_google_lists_font_name",
		"type" => "text"),

    array( "type" => "close"),
    
    array( "name" => "Internet Explorer Fonts",
        "type" => "section"),
    array( "type" => "open"), 

	 array( "name" => "IE Headers",
		"desc" => "Font for IE header elements, such as h1, h2.",
		"id" => $sn."_ie_header_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),
	 array( "name" => "Lists",
		"desc" => "Font for IE list items",
		"id" => $sn."_ie_lists_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),
	 array( "name" => "IE Main Body",
		"desc" => "Font for the main body text of the website",
		"id" => $sn."_ie_body_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),

    array( "type" => "close"),

    array( "name" => "Advanced - IE Custom Elements",
        "type" => "section"),
    array( "type" => "open"), 
    
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_ie_custom_one",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_ie_custom_one_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 

	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_ie_custom_two",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_ie_custom_two_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_ie_custom_three",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_ie_custom_three_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_ie_custom_four",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_ie_custom_four_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList), 
		
	 array( "name" => "Element",
		"desc" => "Enter the ID or class selector for the element you'd like to <em>fontify</em>. For example, <em>#navigation</em>, or <em>.element p</em>",
		"id" => $sn."_ie_custom_five",
		"type" => "text"),    
	 array( "name" => "Element Font",
		"id" => $sn."_ie_custom_five_font",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontList),

	array( "type" => "close"),

    array( "name" => "Font Sizes",
        "type" => "section"),
    array( "type" => "open"),

	 array( "name" => "Header Size",
		"desc" => "Font size for header elements, such as h1, h2.",
		"id" => $sn."_header_font_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
	 array( "name" => "List Size",
		"desc" => "Font size for list items",
		"id" => $sn."_lists_font_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
	 array( "name" => "Main Body Size",
		"desc" => "Font size for the main body text of the website",
		"id" => $sn."_body_font_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
		
	 array( "name" => "Custom Element One",
		"desc" => "Enter the element id that you would like to control the size of<br/>Example: <em>.navigation li</em>",
		"id" => $sn."_custom_one_size_element",
		"type" => "text"),		
	 array( "name" => "Custom Element One Font Size",
		"desc" => "Choose the font size for the custom element defined above.",
		"id" => $sn."_custom_one_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
		
	 array( "name" => "Custom Element Two",
		"desc" => "Enter the element id that you would like to control the size of<br/>Example: <em>.navigation li</em>",
		"id" => $sn."_custom_two_size_element",
		"type" => "text"),		
	 array( "name" => "Custom Element Two Font Size",
		"desc" => "Choose the font size for the custom element defined above.",
		"id" => $sn."_custom_two_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
		
	 array( "name" => "Custom Element Three",
		"desc" => "Enter the element id that you would like to control the size of<br/>Example: <em>.navigation li</em>",
		"id" => $sn."_custom_three_size_element",
		"type" => "text"),		
	 array( "name" => "Custom Element Three Font Size",
		"desc" => "Choose the font size for the custom element defined above.",
		"id" => $sn."_custom_three_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),

	 array( "name" => "Custom Element Four",
		"desc" => "Enter the element id that you would like to control the size of<br/>Example: <em>.navigation li</em>",
		"id" => $sn."_custom_four_size_element",
		"type" => "text"),		
	 array( "name" => "Custom Element Four Font Size",
		"desc" => "Choose the font size for the custom element defined above.",
		"id" => $sn."_custom_four_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
		
	 array( "name" => "Custom Element Five",
		"desc" => "Enter the element id that you would like to control the size of<br/>Example: <em>.navigation li</em>",
		"id" => $sn."_custom_five_size_element",
		"type" => "text"),		
	 array( "name" => "Custom Element Five Font Size",
		"desc" => "Choose the font size for the custom element defined above.",
		"id" => $sn."_custom_five_size",
		"class" => "fu_font_list",
		"type" => "select",
		"options" => $fontSizes),
		
		
    array( "type" => "close"),	
);
?>