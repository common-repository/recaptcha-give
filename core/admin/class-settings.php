<?php
/**
 * Settings class
 *
 * @package reCAPTCHAGive
 */

namespace ReCAPTCHA_Give\Core\Admin;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Settings
 */
class Settings {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'options-general.php',
			__( 'reCAPTCHA for Give Settings', 'recaptchagive' ),
			__( 'reCAPTCHA for Give', 'recaptchagive' ),
			'manage_options',
			'recaptchagive-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( 'recaptchagive', 'recaptchagive' );

		foreach ( \ReCAPTCHA_Give\Core\Settings::get_sections() as $key => $section ) {
			add_settings_section(
				'recaptchagive_' . $key,
				$section['title'],
				array( $this, 'settings_section' ),
				'recaptchagive'
			);
		}

		foreach ( \ReCAPTCHA_Give\Core\Settings::get_settings() as $key => $setting ) {
			$options = array(
				'label_for' => $key,
				'type'      => $setting['type'],
			);

			if ( ! empty( $setting['options'] ) ) {
				$options['options'] = $setting['options'];
			}

			if ( ! empty( $setting['value'] ) ) {
				$options['value'] = $setting['value'];
			}

			if ( ! empty( $setting['placeholder'] ) ) {
				$options['placeholder'] = $setting['placeholder'];
			}

			if ( ! empty( $setting['class'] ) ) {
				$options['class'] = $setting['class'];
			}

			if ( ! empty( $setting['desc'] ) ) {
				$options['desc'] = $setting['desc'];
			}

			if ( ! empty( $setting['suffix'] ) ) {
				$options['suffix'] = $setting['suffix'];
			}

			if ( ! empty( $setting['min'] ) ) {
				$options['min'] = $setting['min'];
			}

			if ( ! empty( $setting['max'] ) ) {
				$options['max'] = $setting['max'];
			}

			if ( ! empty( $setting['step'] ) ) {
				$options['step'] = $setting['step'];
			}

			if ( ! empty( $setting['html'] ) ) {
				$options['html'] = $setting['html'];
			}

			if ( ! empty( $setting['field_class'] ) ) {
				$options['field_class'] = $setting['field_class'];
			}

			if ( ! empty( $setting['multiple'] ) ) {
				$options['multiple'] = $setting['multiple'];
			}

			add_settings_field(
				$key,
				$setting['title'],
				array( $this, 'settings_field' ),
				'recaptchagive',
				'recaptchagive_' . $setting['section'],
				$options
			);
		}
	}

	/**
	 * Settings section
	 *
	 * @param array $args Section arguments.
	 */
	public function settings_section( $args ) {
	}

	/**
	 * Settings field
	 *
	 * @param array $args Field arguments.
	 */
	public function settings_field( $args ) {
		switch ( $args['type'] ) {
			case 'textarea':
				?>
				<textarea
					id="<?php echo esc_attr( $args['label_for'] ); ?>"
					name="recaptchagive[<?php echo esc_attr( $args['label_for'] ); ?>]"
					rows="5"
					<?php if ( ! empty( $args['field_class'] ) ) : ?>
						class="<?php echo esc_attr( $args['field_class'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['placeholder'] ) ) : ?>
						placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php endif; ?>
				><?php if ( ! empty( $args['value'] ) ) : ?><?php echo esc_attr( $args['value'] ); ?><?php endif; ?></textarea>
				<?php
				break;
			case 'url':
			case 'text':
			case 'password':
			case 'number':
			case 'email':
				?>
				<input
					id="<?php echo esc_attr( $args['label_for'] ); ?>"
					name="recaptchagive[<?php echo esc_attr( $args['label_for'] ); ?>]"
					type="<?php echo esc_attr( $args['type'] ); ?>"
					<?php if ( ! empty( $args['value'] ) ) : ?>
						value="<?php echo esc_attr( $args['value'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['field_class'] ) ) : ?>
						class="<?php echo esc_attr( $args['field_class'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['placeholder'] ) ) : ?>
						placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['min'] ) ) : ?>
						min="<?php echo esc_attr( $args['min'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['max'] ) ) : ?>
						max="<?php echo esc_attr( $args['max'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['step'] ) ) : ?>
						step="<?php echo esc_attr( $args['step'] ); ?>"
					<?php endif; ?>
				/>
				<?php
				break;
			case 'select':
				if ( empty( $args['options'] ) ) {
					return;
				}

				$name = 'recaptchagive[' . esc_attr( $args['label_for'] ) . ']';
				if ( ! empty( $args['multiple'] ) ) :
					$name = 'recaptchagive[' . esc_attr( $args['label_for'] ) . '][]';
				endif;
				?>
				<select
					id="<?php echo esc_attr( $args['label_for'] ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					<?php if ( ! empty( $args['multiple'] ) ) : ?>
						multiple
					<?php endif; ?>
					<?php if ( ! empty( $args['field_class'] ) ) : ?>
						class="<?php echo esc_attr( $args['field_class'] ); ?>"
					<?php endif; ?>
				>
						<?php
						foreach ( $args['options'] as $key => $label ) :
							$selected = false;
							if ( ! empty( $args['value'] ) && ! empty( $args['multiple'] ) && is_array( $args['value'] ) ) :
								if ( in_array( $key, $args['value'], true ) ) :
									$selected = true;
								endif;
							else :
								if ( ! empty( $args['value'] ) && $args['value'] == $key ) {
									$selected = true;
								}
							endif;
							?>
							<option
								value="<?php echo esc_attr( $key ); ?>"
								<?php if ( $selected ) : ?>
									selected="selected"
								<?php endif; ?>
							>
								<?php esc_html_e( $label ); ?>
							</option>
						<?php endforeach; ?>
				</select>
				<?php
				break;
			case 'checkbox':
			case 'radio':
				if ( empty( $args['options'] ) ) {
					return;
				}

				foreach ( $args['options'] as $key => $label ) {
					$selected = false;
					$name     = 'recaptchagive[' . esc_attr( $args['label_for'] ) . ']';
					if ( count( $args['options'] ) > 1 && 'checkbox' === $args['type'] ) {
						$name .= '[' . esc_attr( $key ) . ']';
					}

					if ( ! empty( $args['value'] ) && $args['value'] == $key ) {
						$selected = true;
					}

					?>
					<label for="<?php echo esc_attr( $args['label_for'] . $key ); ?>">
						<input
							type="<?php echo esc_attr( $args['type'] ); ?>"
							id="<?php echo esc_attr( $args['label_for'] . $key ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							value="<?php echo esc_attr( $key ); ?>"
							<?php if ( ! empty( $args['field_class'] ) ) : ?>
								class="<?php echo esc_attr( $args['field_class'] ); ?>"
							<?php endif; ?>
							<?php if ( $selected ) : ?>
								checked="checked"
							<?php endif; ?>
						/>
						<?php
						echo wp_kses(
							$label,
							array(
								'a' => array(
									'target' => array(),
									'href'   => array(),
									'class'  => array(),
									'rel'    => array(),
								),
								'strong' => array(),
								'b'      => array(),
								'code'   => array(),
							)
						);
						?>
					</label><br />
					<?php
				}
				break;
		}

		if ( ! empty( $args['suffix'] ) ) {
			echo wp_kses(
				$args['suffix'],
				array(
					'a' => array(
						'target' => array(),
						'href'   => array(),
						'class'  => array(),
						'rel'    => array(),
					),
					'strong' => array(),
					'b'      => array(),
					'code'   => array(),
				)
			);
		}

		if ( ! empty( $args['desc'] ) ) {
			echo '<p class="description">' . wp_kses(
				$args['desc'],
				array(
					'a'      => array(
						'target' => array(),
						'href'   => array(),
						'class'  => array(),
						'rel'    => array(),
					),
					'strong' => array(),
					'b'      => array(),
					'code'   => array(),
				)
			) . '</p>';
		}
	}

	/**
	 * Settings page
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form action="options.php" method="post">
			<?php
			// Output security fields for the registered setting "recaptchagive".
			settings_fields( 'recaptchagive' );

			echo '<div class="recaptchagive-settings-tabs">';
			// Output setting sections and their fields.
			do_settings_sections( 'recaptchagive' );

			// Output save settings button.
			submit_button( __( 'Save Settings', 'recaptchagive' ) );
			?>
			</form>
		</div>
		<?php
	}
}
