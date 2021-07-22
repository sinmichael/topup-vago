<?php

$settings = array(
        array(
            'name' => __( 'General Configuration', 'topup-vago' ),
            'type' => 'title',
            'id'   => $prefix . 'general_config_settings'
        ),
        array(
            'id'        => $prefix . 'username',
            'name'      => __( 'Username', 'topup-vago' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' Your Topup Vago username', 'topup-vago')
        ),
        array(
            'id'        => $prefix . 'password',
            'name'      => __( 'Password', 'topup-vago' ), 
            'type'      => 'password',
            'desc_tip'  => __( ' Your Topup Vago password', 'topup-vago')
        ),
        array(
            'id'        => '',
            'name'      => __( 'General Configuration', 'topup-vago' ),
            'type'      => 'sectionend',
            'desc'      => '',
            'id'        => $prefix . 'general_config_settings'
        ),
        array(
            'name' => __( 'Custom Messages', 'topup-vago' ),
            'type' => 'title',
            'id'   => $prefix . 'custom_messages_settings'
        ),
        array(
            'id'        => $prefix . 'before-add-to-cart-form-text',
            'name'      => __( 'Before add to cart form text', 'topup-vago' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' Before add to cart form text', 'topup-vago')
        ),
        array(
            'id'        => '',
            'name'      => __( 'Custom Messages', 'topup-vago' ),
            'type'      => 'sectionend',
            'desc'      => '',
            'id'        => $prefix . 'custom_messages_settings'
        ),
        array(
            'id'        => $prefix . 'enable-logging',
            'type'      => 'checkbox',
            'desc'  => __( 'Enable logging?', 'topup-vago' ),
            'default'   => 'no'
        ),  
    );

?>