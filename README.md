# Cartthrob Email Extension

This extension uses the cartthrob_on_authorize hook from the ExpressionEngine ecommerce software, Cartthrob, to send an email. The 'to' field is pulled from a field in the order. 

## Instructions
1. Download and put the email_extension folder in your addons folder. 
2. Create a text field in the "Orders" Channel. Can be called whateever you want.
3. In the ext.email_extension.php file, replace CUSTOM_FIELD_HERE with the field name.
4. Also in the ext.email_extension.php file, fill in all the placeholder text with your actual information (FROM_EMAIL_HERE, FROM_NAME_HERE, EMAIL_SUBJECT_HERE, EMAIL_TEMPLATE_HERE)
5. EMAIL_TEMPLATE_HERE will need to be the path to your email template (i.e. emails/confirmation_email)


