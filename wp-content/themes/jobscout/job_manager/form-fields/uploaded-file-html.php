<?php
/**
 * Shows info for an uploaded file on job listing forms (Vietnamese).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="job-manager-uploaded-file" style="display: inline-flex; align-items: center; gap: 12px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 14px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
	<?php
	if ( is_numeric( $value ) ) {
		$image_src = wp_get_attachment_image_src( absint( $value ), 'thumbnail' );
		$image_src = $image_src ? $image_src[0] : '';
	} else {
		$image_src = $value;
	}
	if ( function_exists( 'jobscout_normalize_media_host' ) ) {
		$image_src = jobscout_normalize_media_host( $image_src );
	}
	$extension = ! empty( $extension ) ? $extension : substr( strrchr( $image_src, '.' ), 1 );
	if ( 'image' === wp_ext2type( $extension ) || in_array( strtolower( $extension ), array( 'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif', 'bmp' ) ) ) : ?>
		<span class="job-manager-uploaded-file-preview" style="display: inline-flex; align-items: center; gap: 12px;">
			<img src="<?php echo esc_url( $image_src ); ?>" style="width: 52px; height: 52px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; padding: 2px;" />
			<a class="job-manager-remove-uploaded-file" href="#" style="color: #ef4444; font-size: 13px; font-weight: 600; text-decoration: none; padding: 5px 10px; border-radius: 5px; background: #fee2e2; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease;">
				<i class="fas fa-trash-alt"></i> Xóa ảnh
			</a>
		</span>
	<?php else : ?>
		<span class="job-manager-uploaded-file-name" style="display: inline-flex; align-items: center; gap: 12px;">
			<code style="font-size: 13px; color: #334155; font-weight: 600;"><?php echo esc_html( basename( $image_src ) ); ?></code>
			<a class="job-manager-remove-uploaded-file" href="#" style="color: #ef4444; font-size: 13px; font-weight: 600; text-decoration: none; padding: 5px 10px; border-radius: 5px; background: #fee2e2; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease;">
				<i class="fas fa-trash-alt"></i> Xóa file
			</a>
		</span>
	<?php endif; ?>

	<input type="hidden" class="input-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
</div>
