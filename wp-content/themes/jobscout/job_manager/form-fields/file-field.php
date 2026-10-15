<?php
/**
 * Shows the `file` form field on job listing forms (Supports ANY image format and size).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$classes            = [ 'input-text' ];
$allowed_mime_types = ! empty( $field['allowed_mime_types'] ) ? $field['allowed_mime_types'] : job_manager_get_allowed_mime_types( $key );
$allowed_extensions = array_keys( $allowed_mime_types );
$field_name         = isset( $field['name'] ) ? $field['name'] : $key;
$field_name         .= ! empty( $field['multiple'] ) ? '[]' : '';
$file_limit         = false;

if ( ! empty( $field['multiple'] ) && ! empty( $field['file_limit'] ) ) {
	$file_limit = $field['file_limit'];
}

if ( ! empty( $field['ajax'] ) && job_manager_user_can_upload_file_via_ajax() ) {
	wp_enqueue_script( 'wp-job-manager-ajax-file-upload' );
	$classes[] = 'wp-job-manager-file-upload';
}
?>
<div class="job-manager-uploaded-files" style="margin-bottom: 10px;">
	<?php if ( ! empty( $field['value'] ) ) : ?>
		<?php if ( is_array( $field['value'] ) ) : ?>
			<?php foreach ( $field['value'] as $value ) : ?>
				<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', [ 'key' => $key, 'name' => 'current_' . $field_name, 'value' => $value, 'field' => $field ] ); ?>
			<?php endforeach; ?>
		<?php elseif ( $value = $field['value'] ) : ?>
			<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', [ 'key' => $key, 'name' => 'current_' . $field_name, 'value' => $value, 'field' => $field ] ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>

<input
	type="file"
	class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-file_types="<?php echo esc_attr( implode( '|', $allowed_extensions ) ); ?>"
	accept="image/*"
	<?php if ( ! empty( $field['required'] ) ) echo ' required'; ?>
	<?php if ( ! empty( $field['multiple'] ) ) echo ' multiple'; ?>
	<?php if ( $file_limit ) echo ' data-file_limit="' . absint( $file_limit ) . '"';?>
	<?php if ( ! empty( $field['file_limit_message'] ) ) echo ' data-file_limit_message="' . esc_attr( $field['file_limit_message'] ) . '"';?>
	name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) echo '[]'; ?>"
	id="<?php echo esc_attr( $key ); ?>"
	placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>"
	style="width: 100%; padding: 12px 16px; border: 2px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 14px; cursor: pointer; box-sizing: border-box; display: block;"
/>

<small class="description" style="color: #64748b; font-size: 13px; margin-top: 8px; display: block; line-height: 1.5;">
	<i class="fas fa-check-circle" style="color: #27c96d; margin-right: 4px;"></i>
	Hỗ trợ tải lên mọi định dạng ảnh (JPG, PNG, WEBP, GIF, SVG...). Tự động tối ưu hiển thị theo mọi kích thước và tỷ lệ thực tế.
</small>
