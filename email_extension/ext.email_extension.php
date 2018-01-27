<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Email Extension class
 *
 * @package        email_extension
 * @author         Brianne Hostutler
 * @link           looknorthinc.com
 * @license        http://creativecommons.org/licenses/by-sa/3.0/
 */

class Email_extension_ext {

    var $name           = 'Cartthrob Email Extension';
    var $version        = '1.0.0';
    var $description    = 'Send email to custom field saleperson and additional email when an order is placed';
    var $settings_exist = 'n';
    var $docs_url       = ''; // 'https://ellislab.com/expressionengine/user-guide/';
    var $settings       = array();


    /**
     * Activate Extension
     *
     * @return void
     */
    public function activate_extension()
    {
        ee()->db->insert('extensions', array(
            'class' => __CLASS__,
            'method' => 'cartthrob_on_authorize',
            'hook' => 'cartthrob_on_authorize',
            'settings' => '',
            'priority' => 10,
            'version' => $this->version,
            'enabled' => 'y'
        ));

        $this->EE =& get_instance();
        // add the package path so we can access CT scripts & libraries
        $this->EE->load->add_package_path(PATH_THIRD.'cartthrob/');
        // load CT in. IMPORTANT!
        $this->EE->load->library('cartthrob_loader');
         
        $this->notification_events = array('salesperson_send_email'); 
        // this does not have to be the same as the file name. It's arbitrary, make it say whatever you want.
        // The module name will be used in the event registration list. For example: My_module:my_notification_event would show up in the notifications drop-down as a selectable event. 
        $this->module_name = "email_extension"; 
         
        /////////////// NOTIFICATIONS /////////////////////////
        if (!empty($this->notification_events))
        {
            $this->EE->db->select('notification_event')
                    ->from('cartthrob_notification_events')
                    ->like('application', ucwords($this->module_name), 'after');
         
            $existing_notifications = array();
         
            foreach ($this->EE->db->get()->result() as $row)
            {
                $existing_notifications[] = $row->notification_event;
            }
            
            foreach ($this->notification_events as $event)
            {
                if (!empty($event))
                {
                    if ( ! in_array($event, $existing_notifications))
                    {
                        $this->EE->db->insert(
                            'cartthrob_notification_events',
                            array(
                                'application' => ucwords($this->module_name),
                                'notification_event' => $event,
                            )
                        );
                    }
                }
            }
        }

    } 

    /**
     * Disable Extension
     *
     * @return void
     */
    public function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }


    public function cartthrob_on_authorize()
    {
        // error_reporting(-1);
        $this->EE =& get_instance();
        // add the package path so we can access CT scripts & libraries
        $this->EE->load->add_package_path(PATH_THIRD.'cartthrob/');
        // load CT in. IMPORTANT!
        $this->EE->load->library('cartthrob_loader');
        $this->EE->load->library('cartthrob_emails');
        
        // make sure whatever you use here is the same module name you used when you installed the notifications.
        $this->module_name = "email_extension";


       $custom_data = $this->EE->cartthrob->cart->order('custom_data');
        if (!empty($custom_data['order-salesperson_email'])){

        }

        $this->EE->cartthrob->cart->order(); 

        $custom_data = $this->EE->cartthrob->cart->order('custom_data'); 

        $company = $this->EE->cartthrob->cart->order('order_billing_company'); 
        $num = $this->EE->cartthrob->cart->order('title'); 


        $subject = $num . ' FOODMatch Sample Confirmation ' . $company;

        foreach ($custom_data as $key => $value) {
            $salesEmail = $custom_data['order-salesperson_email'];
        }
        //sends email to salesperson
        $email_content['to']= $salesEmail;
        $email_content['from'] = "samples@foodmatch.com";
        $email_content['from_name'] = "FOODMatch";
        $email_content['subject'] = $subject;
        $email_content['message'] = "Test messaging";
        $email_content['message_template'] = "email/admin_successful_request";

        $this->EE->cartthrob_emails->send_email($email_content);
        
        
    }
}
?>