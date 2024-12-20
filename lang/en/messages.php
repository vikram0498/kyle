<?php
return [
    'add_success_message'    => 'Added successfully',
    'edit_success_message'   => 'Updated successfully',
    'delete_success_message' => 'Deleted successfully',
    'change_status_success_message' => 'Status successfully changed',
    'level_3_status_success_message' => 'Level 3 Status successfully changed',
    'seller_block_mesage' => 'Seller blocked successfully',
    'seller_unblock_mesage' => 'Seller unblocked successfully',
    'error_message'   => 'Something went wrong....please try again later!',
    'no_record_found' => 'No Records Found!',
    'wrong_video_extension' => 'Please use the video format mp4 only!',

    'email_messages' => [
       

    ],

    'auth'=>[
        'buyer'=>[
            // 'register_success_alert'=>'Buyer register successfully! We\'ve sent an email with a verification link.',
            'admin_register_success_alert'=>'Buyer registered successfully! We\'ve sent an email to finalize their account setup',
            'register_success_alert'=>'Success! Check your email to finalize your account setup',
            'update_buyer_success_alert'=>'Buyer updated successfully!',

            // 'update_success_with_mail_sent_alert'=>'Buyer updated successfully! We\'ve sent an email with a verification link.',
            'admin_update_success_with_mail_sent_alert'=>'Buyer updated successfully! We\'ve sent an email to finalize their account setup',

            'update_success_with_mail_sent_alert'=>'Success! Check your email to finalize your account setup',

            'upgrade_error'=>'Please upgrade your account first!',
            'verify_profile'=> 'Please verify your profile first!',
        ],
        'verification'=>[
            'invalid_otp'         => 'Invalid OTP Number!',
            'otp_send_success'    => 'OTP has been sent!',
            'phone_verify_success'=> 'Phone Verified Successfully!',
            'driver_license_success'=> 'ID Submitted Successfully!',
            'proof_of_funds_success'=> 'Proof Of Funds Submitted Successfully!',
            'llc_verification_success'=> 'LLC Submitted Successfully!',
            'certified_closure_success'=> 'Certified Closure Submitted Successfully!',
            'application_process_success'=> 'Application Payment Successfully!',
            'profile_upload_success' => 'Your profile image has been successfully uploaded!',

        ],
    ],

    'csv_file'=>[
        'empty' => 'The CSV file is empty.',
        'too_many_rows'  => 'The CSV file contains too many rows. Maximum allowed rows: :limit',
        'import_success' => 'CSV file has been imported successfully.',
        'buyer_invitation_success' => 'Invitation Link Sent Successfully!',
    ],

    'no_row_selected' => 'Please select at least one row.',
    'reminder_sent_success' => 'Reminder Mail Sent Successfully!',

    'buyer_deal' => [
        'success_send_deal' => "Successfully sent deal notifications to :total_buyer buyer",
        'already_updated' => "Deal status is already updated to :status",
        'not_found' => "Deal not found"
    ],

    'update_status' => ":module_name status updated successfully",

    'otp_sms_content' => "Your verification code is :otpNumber. Please enter this code to complete your verification. Do not share this code with anyone. The code will expire in :otpTime minutes.",
    
    'chat_message' => [
        'success_send_message'      => "Message sent successfully.",
        'no_message_found'          => "No messages found for this conversation.",
        'no_conversation_found'     => "No Conversation found.",
        'marked_as_read_successfully'  => "Message successfully marked as read.",
        'user_block_success'           => "Blocked successfully.", 
        'added_wishlist_success'       => "Added to wishlist successfully!",
        'removed_wishlist_success'     => "Removed from your wishlist successfully!",
        'not_in_wishlist'              => "The user is not in your wishlist.",
        'yourself_not_to_add_wishlist' => "You cannot add yourself to the wishlist.",
        'already_added_wishlist'       => "Already in your wishlist.",
        'conversation_already_reported' => "Conversation already reported",
        'conversation_added_to_report'  => "Conversation added to report"

    ]
    
];