<?php
/**
 * Static functions used in the Kadence Starter Templates plugin.
 *
 * @package Kadence Starter Templates
 */

namespace KadenceWP\KadenceStarterTemplates;

use function request_filesystem_credentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class with static helper functions.
 */
class Helpers {
	/**
	 * Holds the date and time string for demo import and log file.
	 *
	 * @var string
	 */
	public static $demo_import_start_time = '';

	/**
	 * Filter through the array of import files and get rid of those who do not comply.
	 *
	 * @param  array $import_files list of arrays with import file details.
	 * @return array list of filtered arrays.
	 */
	public static function validate_import_file_info( $import_files ) {
		$filtered_import_file_info = array();

		foreach ( $import_files as $import_file ) {
			if ( self::is_import_file_info_format_correct( $import_file ) ) {
				$filtered_import_file_info[] = $import_file;
			}
		}

		return $filtered_import_file_info;
	}


	/**
	 * Helper function: a simple check for valid import file format.
	 *
	 * @param  array $import_file_info array with import file details.
	 * @return boolean
	 */
	private static function is_import_file_info_format_correct( $import_file_info ) {
		if ( empty( $import_file_info['slug'] ) ) {
			return false;
		}

		return true;
	}
	/**
	 * Download import files. Content .xml and widgets .wie|.json files.
	 *
	 * @param  array  $import_file_info array with import file details.
	 * @return array|WP_Error array of paths to the downloaded files or WP_Error object with error message.
	 */
	public static function download_import_file( $import_file_info, $page_id ) {
		$downloaded_files = array(
			'content'    => '',
			'customizer' => '',
		);
		$downloader = new Downloader();

		// ----- Set content file path -----
		// Set the filename string for content import file.
		$content_filename = apply_filters( 'kadence-starter-templates/downloaded_content_file_prefix', 'demo-content-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_content_file_suffix_and_file_extension', '.xml' );

		// Download the content import file.
		$downloaded_files['content'] = $downloader->download_file( $import_file_info['pages'][ $page_id ]['content'], $content_filename );

		// Return from this function if there was an error.
		if ( is_wp_error( $downloaded_files['content'] ) ) {
			return $downloaded_files['content'];
		}
		// ----- Set customizer file path -----
		// Get customizer import file as well. If defined!
		if ( ! empty( $import_file_info['theme_options'] ) ) {
			// Setup filename path to save the customizer content.
			$customizer_filename = apply_filters( 'kadence-starter-templates/downloaded_customizer_file_prefix', 'demo-customizer-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_customizer_file_suffix_and_file_extension', '.dat' );

			// Download the customizer import file.
			$downloaded_files['customizer'] = $downloader->download_file( $import_file_info['theme_options'], $customizer_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['customizer'] ) ) {
				return $downloaded_files['customizer'];
			}
		}

		return $downloaded_files;
	}

	/**
	 * Download import files. Content .xml and widgets .wie|.json files.
	 *
	 * @param  array  $import_file_info array with import file details.
	 * @return array|WP_Error array of paths to the downloaded files or WP_Error object with error message.
	 */
	public static function download_import_files( $import_file_info ) {
		$downloaded_files = array(
			'content'    => '',
			'widgets'    => '',
			'customizer' => '',
			'redux'      => '',
			'forms'      => '',
		);
		$downloader = new Downloader();
		// ----- Set content file path -----
		// Check if 'content' is not defined. That would mean a local file.
		
		if ( empty( $import_file_info['content'] ) ) {
			if ( isset( $import_file_info['local_content'] ) && file_exists( $import_file_info['local_content'] ) ) {
				$downloaded_files['content'] = $import_file_info['local_content'];
			}
		} else {
			// Set the filename string for content import file.
			$content_filename = apply_filters( 'kadence-starter-templates/downloaded_content_file_prefix', 'demo-content-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_content_file_suffix_and_file_extension', '.xml' );
			// Download the content import file.
			$downloaded_files['content'] = $downloader->download_file( $import_file_info['content'], $content_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['content'] ) ) {
				return $downloaded_files['content'];
			}
		}

		// ----- Set widget file path -----
		// Get widgets file as well. If defined!
		if ( ! empty( $import_file_info['widget_data'] ) ) {
			// Set the filename string for widgets import file.
			$widget_filename = apply_filters( 'kadence-starter-templates/downloaded_widgets_file_prefix', 'demo-widgets-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_widgets_file_suffix_and_file_extension', '.json' );

			// Download the widgets import file.
			$downloaded_files['widgets'] = $downloader->download_file( $import_file_info['widget_data'], $widget_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['widgets'] ) ) {
				return $downloaded_files['widgets'];
			}
		} else if ( ! empty( $import_file_info['local_widget_data'] ) ) {
			if ( file_exists( $import_file_info['local_widget_data'] ) ) {
				$downloaded_files['widgets'] = $import_file_info['local_widget_data'];
			}
		}

		// ----- Set customizer file path -----
		// Get customizer import file as well. If defined!
		if ( ! empty( $import_file_info['theme_options'] ) ) {
			// Setup filename path to save the customizer content.
			$customizer_filename = apply_filters( 'kadence-starter-templates/downloaded_customizer_file_prefix', 'demo-customizer-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_customizer_file_suffix_and_file_extension', '.dat' );

			// Download the customizer import file.
			$downloaded_files['customizer'] = $downloader->download_file( $import_file_info['theme_options'], $customizer_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['customizer'] ) ) {
				return $downloaded_files['customizer'];
			}
		} else if ( ! empty( $import_file_info['local_theme_options'] ) ) {
			if ( file_exists( $import_file_info['local_theme_options'] ) ) {
				$downloaded_files['customizer'] = $import_file_info['local_theme_options'];
			}
		}
		// ----- Set form file path -----
		// Get form file as well. If defined!
		if ( ! empty( $import_file_info['form_data'] ) ) {
			// Set the filename string for form import file.
			$form_filename = apply_filters( 'kadence-starter-templates/downloaded_forms_file_prefix', 'demo-forms-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_form_file_suffix_and_file_extension', '.json' );

			// Download the form import file.
			$downloaded_files['forms'] = $downloader->download_file( $import_file_info['form_data'], $form_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['forms'] ) ) {
				return $downloaded_files['forms'];
			}
		} else if ( ! empty( $import_file_info['local_form_data'] ) ) {
			if ( file_exists( $import_file_info['local_form_data'] ) ) {
				$downloaded_files['forms'] = $import_file_info['local_form_data'];
			}
		}
		// ----- Set give form file path -----
		// Get form file as well. If defined!
		if ( ! empty( $import_file_info['give_donation_data'] ) ) {
			// Set the filename string for form import file.
			$give_filename = apply_filters( 'kadence-starter-templates/downloaded_forms_file_prefix', 'demo-give-donations-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_give_donations_file_suffix_and_file_extension', '.json' );

			// Download the form import file.
			$downloaded_files['give-donations'] = $downloader->download_file( $import_file_info['give_donation_data'], $give_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['give-donations'] ) ) {
				return $downloaded_files['give-donations'];
			}
		}
		// ----- Set give form file path -----
		// Get form file as well. If defined!
		if ( ! empty( $import_file_info['give_form_data'] ) ) {
			// Set the filename string for form import file.
			$give_form_filename = apply_filters( 'kadence-starter-templates/downloaded_forms_file_prefix', 'demo-give-forms-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_give_form_file_suffix_and_file_extension', '.json' );

			// Download the form import file.
			$downloaded_files['give-forms'] = $downloader->download_file( $import_file_info['give_form_data'], $give_form_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['give-forms'] ) ) {
				return $downloaded_files['give-forms'];
			}
		}
		// Get the slider
		if ( ! empty( $import_file_info['depicter_data'] ) ) {
			// Set the filename string for form import file.
			$depicter_filename = apply_filters( 'kadence-starter-templates/downloaded_depicter_file_prefix', 'demo-depicter-import-file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/downloaded_depicter_file_suffix_and_file_extension', '.zip' );

			// Download the form import file.
			$downloaded_files['depicter'] = $downloader->download_file( $import_file_info['depicter_data'], $depicter_filename );

			// Return from this function if there was an error.
			if ( is_wp_error( $downloaded_files['depicter'] ) ) {
				return $downloaded_files['depicter'];
			}
		}

		return $downloaded_files;
	}


	/**
	 * Write content to a file.
	 *
	 * @param string $content content to be saved to the file.
	 * @param string $file_path file path where the content should be saved.
	 * @return string|WP_Error path to the saved file or WP_Error object with error message.
	 */
	public static function write_to_file( $content, $file_path ) {
		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to create a file.
		global $wp_filesystem;

		if ( ! $wp_filesystem->put_contents( $file_path, $content ) ) {
			return new \WP_Error(
				'failed_writing_file_to_server',
				sprintf(
					__( 'An error occurred while writing file to your server! Tried to write a file to: %s%s.', 'kadence-starter-templates' ),
					'<br>',
					$file_path
				)
			);
		}

		// Return the file path on successful file write.
		return $file_path;
	}


	/**
	 * Append content to the file.
	 *
	 * @param string $content content to be saved to the file.
	 * @param string $file_path file path where the content should be saved.
	 * @param string $separator_text separates the existing content of the file with the new content.
	 * @return boolean|WP_Error, path to the saved file or WP_Error object with error message.
	 */
	public static function append_to_file( $content, $file_path, $separator_text = '' ) {
		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to create a file.
		global $wp_filesystem;

		$existing_data = '';
		if ( file_exists( $file_path ) ) {
			$existing_data = $wp_filesystem->get_contents( $file_path );
		}

		// Style separator.
		$separator = PHP_EOL . '---' . $separator_text . '---' . PHP_EOL;

		if ( ! $wp_filesystem->put_contents( $file_path, $existing_data . $separator . $content . PHP_EOL ) ) {
			return new \WP_Error(
				'failed_writing_file_to_server',
				sprintf(
					__( 'An error occurred while writing file to your server! Tried to write a file to: %s%s.', 'kadence-starter-templates' ),
					'<br>',
					$file_path
				)
			);
		}

		return true;
	}


	/**
	 * Get data from a file
	 *
	 * @param string $file_path file path where the content should be saved.
	 * @return string $data, content of the file or WP_Error object with error message.
	 */
	public static function data_from_file( $file_path ) {
		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to read a file.
		global $wp_filesystem;

		$data = $wp_filesystem->get_contents( $file_path );

		if ( ! $data ) {
			return new \WP_Error(
				'failed_reading_file_from_server',
				sprintf(
					__( 'An error occurred while reading a file from your server! Tried reading file from path: %s%s.', 'kadence-starter-templates' ),
					'<br>',
					$file_path
				)
			);
		}

		// Return the file data.
		return $data;
	}


	/**
	 * Helper function: check for WP file-system credentials needed for reading and writing to a file.
	 *
	 * @return boolean|WP_Error
	 */
	private static function check_wp_filesystem_credentials() {
		// Check if the file-system method is 'direct', if not display an error.
		$file_system_method = apply_filters( 'kadence-starter-templates/file_system_method', 'direct' );
		if ( ! ( $file_system_method === get_filesystem_method() ) ) {
			return new \WP_Error(
				'no_direct_file_access',
				sprintf(
					__( 'This WordPress page does not have %sdirect%s write file access. This plugin needs it in order to save the demo import xml file to the upload directory of your site. You can change this setting with these instructions: %s.', 'kadence-starter-templates' ),
					'<strong>',
					'</strong>',
					'<a href="http://gregorcapuder.com/wordpress-how-to-set-direct-filesystem-method/" target="_blank">How to set <strong>direct</strong> filesystem method</a>'
				)
			);
		}

		// Get plugin page settings.
		$plugin_page_setup = apply_filters( 'kadence-starter-templates/plugin_page_setup', array(
				'parent_slug' => 'themes.php',
				'page_title'  => esc_html__( 'One Click Demo Import' , 'kadence-starter-templates' ),
				'menu_title'  => esc_html__( 'Import Demo Data' , 'kadence-starter-templates' ),
				'capability'  => 'import',
				'menu_slug'   => 'pt-one-click-demo-import',
			)
		);

		// Get user credentials for WP file-system API.
		$demo_import_page_url = wp_nonce_url( $plugin_page_setup['parent_slug'] . '?page=' . $plugin_page_setup['menu_slug'], $plugin_page_setup['menu_slug'] );

		if ( false === ( $creds = request_filesystem_credentials( $demo_import_page_url, '', false, false, null ) ) ) {
			return new \WP_error(
				'filesystem_credentials_could_not_be_retrieved',
				__( 'An error occurred while retrieving reading/writing permissions to your server (could not retrieve WP filesystem credentials)!', 'kadence-starter-templates' )
			);
		}

		// Now we have credentials, try to get the wp_filesystem running.
		if ( ! WP_Filesystem( $creds ) ) {
			return new \WP_Error(
				'wrong_login_credentials',
				__( 'Your WordPress login credentials don\'t allow to use WP_Filesystem!', 'kadence-starter-templates' )
			);
		}

		return true;
	}


	/**
	 * Get log file path
	 *
	 * @return string, path to the log file
	 */
	public static function get_log_path() {
		$upload_dir  = wp_upload_dir();
		$upload_path = apply_filters( 'kadence-starter-templates/upload_file_path', trailingslashit( $upload_dir['path'] ) );

		$log_path = $upload_path . apply_filters( 'kadence-starter-templates/log_file_prefix', 'log_file_' ) . self::$demo_import_start_time . apply_filters( 'kadence-starter-templates/log_file_suffix_and_file_extension', '.txt' );

		self::register_file_as_media_attachment( $log_path );

		return $log_path;
	}


	/**
	 * Register file as attachment to the Media page.
	 *
	 * @param string $log_path log file path.
	 * @return void
	 */
	public static function register_file_as_media_attachment( $log_path ) {
		// Check the type of file.
		$log_mimes = array( 'txt' => 'text/plain' );
		$filetype  = wp_check_filetype( basename( $log_path ), apply_filters( 'kadence-starter-templates/file_mimes', $log_mimes ) );

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => self::get_log_url( $log_path ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => apply_filters( 'kadence-starter-templates/attachment_prefix', esc_html__( 'One Click Demo Import - ', 'kadence-starter-templates' ) ) . preg_replace( '/\.[^.]+$/', '', basename( $log_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Insert the file as attachment in Media page.
		$attach_id = wp_insert_attachment( $attachment, $log_path );
	}


	/**
	 * Get log file url
	 *
	 * @param string $log_path log path to use for the log filename.
	 * @return string, url to the log file.
	 */
	public static function get_log_url( $log_path ) {
		$upload_dir = wp_upload_dir();
		$upload_url = apply_filters( 'kadence-starter-templates/upload_file_url', trailingslashit( $upload_dir['url'] ) );

		return $upload_url . basename( $log_path );
	}


	/**
	 * Check if the AJAX call is valid.
	 */
	public static function verify_ajax_call() {
		check_ajax_referer( 'kadence-ajax-verification', 'security' );

		// Check if user has the WP capability to import data.
		if ( ! current_user_can( 'import' ) ) {
			wp_die(
				sprintf(
					__( '%sYour user role isn\'t high enough. You don\'t have permission to import demo data.%s', 'kadence-starter-templates' ),
					'<div class="notice  notice-error"><p>',
					'</p></div>'
				)
			);
		}
	}


	/**
	 * Process uploaded files and return the paths to these files.
	 *
	 * @param array  $uploaded_files $_FILES array form an AJAX request.
	 * @param string $log_file_path path to the log file.
	 * @return array of paths to the content import and widget import files.
	 */
	public static function process_uploaded_files( $uploaded_files, $log_file_path ) {
		// Variable holding the paths to the uploaded files.
		$selected_import_files = array(
			'content'    => '',
			'widgets'    => '',
			'customizer' => '',
			'redux'      => '',
		);

		// Upload settings to disable form and type testing for AJAX uploads.
		$upload_overrides = array(
			'test_form' => false,
			'test_type' => false,
		);

		// Error data if the demo file was not provided.
		$file_not_provided_error = array(
			'error' => esc_html__( 'No file provided.', 'kadence-starter-templates' )
		);

		// Handle demo file uploads.
		$content_file_info = isset( $_FILES['content_file'] ) ?
			wp_handle_upload( $_FILES['content_file'], $upload_overrides ) :
			$file_not_provided_error;

		$widget_file_info = isset( $_FILES['widget_file'] ) ?
			wp_handle_upload( $_FILES['widget_file'], $upload_overrides ) :
			$file_not_provided_error;

		$customizer_file_info = isset( $_FILES['customizer_file'] ) ?
			wp_handle_upload( $_FILES['customizer_file'], $upload_overrides ) :
			$file_not_provided_error;

		$redux_file_info = isset( $_FILES['customizer_file'] ) ?
			wp_handle_upload( $_FILES['redux_file'], $upload_overrides ) :
			$file_not_provided_error;

		// Process content import file.
		if ( $content_file_info && ! isset( $content_file_info['error'] ) ) {
			// Set uploaded content file.
			$selected_import_files['content'] = $content_file_info['file'];
		} else {
			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Add this error to log file.
				$log_added = self::append_to_file(
					sprintf(
						__( 'Content file was not uploaded. Error: %s', 'kadence-starter-templates' ),
						$content_file_info['error']
					),
					$log_file_path,
					esc_html__( 'Upload files' , 'kadence-starter-templates' )
				);
			}
		}

		// Process widget import file.
		if ( $widget_file_info && ! isset( $widget_file_info['error'] ) ) {
			// Set uploaded widget file.
			$selected_import_files['widgets'] = $widget_file_info['file'];
		} else {
			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Add this error to log file.
				$log_added = self::append_to_file(
					sprintf(
						__( 'Widget file was not uploaded. Error: %s', 'kadence-starter-templates' ),
						$widget_file_info['error']
					),
					$log_file_path,
					esc_html__( 'Upload files' , 'kadence-starter-templates' )
				);
			}
		}

		// Process Customizer import file.
		if ( $customizer_file_info && ! isset( $customizer_file_info['error'] ) ) {
			// Set uploaded customizer file.
			$selected_import_files['customizer'] = $customizer_file_info['file'];
		} else {
			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Add this error to log file.
				$log_added = self::append_to_file(
					sprintf(
						__( 'Customizer file was not uploaded. Error: %s', 'kadence-starter-templates' ),
						$customizer_file_info['error']
					),
					$log_file_path,
					esc_html__( 'Upload files' , 'kadence-starter-templates' )
				);
			}
		}

		// Process Redux import file.
		if ( $redux_file_info && ! isset( $redux_file_info['error'] ) ) {
			if ( isset( $_POST['redux_option_name'] ) && empty( $_POST['redux_option_name'] ) ) {
				// Write error to log file and send an AJAX response with the error.
				self::log_error_and_send_ajax_response(
					esc_html__( 'Missing Redux option name! Please also enter the Redux option name!', 'kadence-starter-templates' ),
					$log_file_path,
					esc_html__( 'Upload files', 'kadence-starter-templates' )
				);
			}

			// Set uploaded Redux file.
			$selected_import_files['redux'] = array(
				array(
					'option_name' => sanitize_text_field( $_POST['redux_option_name'] ),
					'file_path'   => $redux_file_info['file'],
				),
			);
		} else {
			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Add this error to log file.
				$log_added = self::append_to_file(
					sprintf(
						__( 'Redux file was not uploaded. Error: %s', 'kadence-starter-templates' ),
						$redux_file_info['error']
					),
					$log_file_path,
					esc_html__( 'Upload files' , 'kadence-starter-templates' )
				);
			}
		}
		if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
			// Add this message to log file.
			$log_added = self::append_to_file(
				__( 'The import files were successfully uploaded!', 'kadence-starter-templates' ) . self::import_file_info( $selected_import_files ),
				$log_file_path,
				esc_html__( 'Upload files' , 'kadence-starter-templates' )
			);
		}

		// Return array with paths of uploaded files.
		return $selected_import_files;
	}


	/**
	 * Get import file information and max execution time.
	 *
	 * @param array $selected_import_files array of selected import files.
	 */
	public static function import_file_info( $selected_import_files ) {

		return PHP_EOL .
		sprintf(
			__( 'Initial max execution time = %s', 'kadence-starter-templates' ),
			ini_get( 'max_execution_time' )
		) . PHP_EOL .
		sprintf(
			__( 'Files info:%1$sSite URL = %2$s%1$sData file = %3$s%1$sWidget file = %4$s%1$sCustomizer file = %5$s%1$sForm File:%1$s%6$s', 'kadence-starter-templates' ),
			PHP_EOL,
			get_site_url(),
			empty( $selected_import_files['content'] ) ? esc_html__( 'not defined!', 'kadence-starter-templates' ) : $selected_import_files['content'],
			empty( $selected_import_files['widgets'] ) ? esc_html__( 'not defined!', 'kadence-starter-templates' ) : $selected_import_files['widgets'],
			empty( $selected_import_files['customizer'] ) ? esc_html__( 'not defined!', 'kadence-starter-templates' ) : $selected_import_files['customizer'],
			empty( $selected_import_files['forms'] ) ? esc_html__( 'not defined!', 'kadence-starter-templates' ) : $selected_import_files['forms']
		);
	}


	/**
	 * Write the error to the log file and send the AJAX response.
	 *
	 * @param string $error_text text to display in the log file and in the AJAX response.
	 * @param string $log_file_path path to the log file.
	 * @param string $separator title separating the old and new content.
	 */
	public static function log_error_and_send_ajax_response( $error_text, $log_file_path, $separator = '' ) {
		if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
			// Add this error to log file.
			$log_added = self::append_to_file(
				$error_text,
				$log_file_path,
				$separator
			);
		}

		// Send JSON Error response to the AJAX call.
		wp_send_json( $error_text );
	}


	/**
	 * Set the $demo_import_start_time class variable with the current date and time string.
	 */
	public static function set_demo_import_start_time() {
		self::$demo_import_start_time = date( apply_filters( 'kadence-starter-templates/date_format_for_file_names', 'Y-m-d__H-i-s' ) );
	}


	/**
	 * Get the category list of all categories used in the predefined demo imports array.
	 *
	 * @param  array $demo_imports Array of demo import items (arrays).
	 * @return array|boolean       List of all the categories or false if there aren't any.
	 */
	public static function get_all_demo_import_categories( $demo_imports ) {
		$categories = array();

		foreach ( $demo_imports as $item ) {
			if ( ! empty( $item['categories'] ) && is_array( $item['categories'] ) ) {
				foreach ( $item['categories'] as $category ) {
					$categories[ sanitize_key( $category ) ] = $category;
				}
			}
		}

		if ( empty( $categories ) ) {
			return false;
		}

		return $categories;
	}


	/**
	 * Return the concatenated string of demo import item categories.
	 * These should be separated by comma and sanitized properly.
	 *
	 * @param  array  $item The predefined demo import item data.
	 * @return string       The concatenated string of categories.
	 */
	public static function get_demo_import_item_categories( $item ) {
		$sanitized_categories = array();

		if ( isset( $item['categories'] ) ) {
			foreach ( $item['categories'] as $category ) {
				$sanitized_categories[] = sanitize_key( $category );
			}
		}

		if ( ! empty( $sanitized_categories ) ) {
			return implode( ',', $sanitized_categories );
		}

		return false;
	}


	/**
	 * Set the Kadence transient with the current importer data.
	 *
	 * @param array $data Data to be saved to the transient.
	 */
	public static function set_import_data_transient( $data ) {
		set_transient( 'kadence_importer_data', $data, 0.1 * HOUR_IN_SECONDS );
	}
}
