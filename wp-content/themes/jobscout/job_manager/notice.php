<?php
/**
 * Custom Notice Component for JobScout (Vietnamese Modern Enterprise Recruitment Portal).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = is_array( $classes ?? null ) ? $classes : array();

$has_actions_footer = ! empty( $actions_html );
if ( $has_actions_footer ) {
	$classes[] = 'has-actions';
}

if ( ! empty( $title ) ) {
	$classes[] = 'has-header';
}

// Default to color-success if not error, ensuring it always displays in vibrant emerald green
$is_error = in_array( 'color-error', $classes, true );
if ( ! $is_error && ! in_array( 'color-success', $classes, true ) ) {
	$classes[] = 'color-success';
}

if ( in_array( 'message-icon', $classes, true ) ) {
	$message_icon_html = $icon_html;
	$icon_html         = '';
}
?>

<div class="jm-notice <?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>" role="status">
	<?php if ( ! empty( $title ) ) : ?>
		<div class="jm-notice__header">
			<?php echo $icon_html; ?>
			<div class="jm-notice__title"><?php echo esc_html( $title ); ?></div>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $message ) ) : ?>
		<div class="jm-notice__message-wrap">
			<?php if ( empty( $title ) && ! empty( $icon_html ) ) : ?>
				<?php echo $icon_html; ?>
			<?php endif; ?>
			<?php echo $message_icon_html ?? ''; ?>
			<div class="jm-notice__message <?php echo esc_attr( ! empty( $content_html ) ? 'has-details' : '' ); ?>">
				<?php echo wp_kses_post( $message ); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $content_html ) ) : ?>
		<div class="jm-notice__details"><?php echo $content_html; ?></div>
	<?php endif; ?>
	<?php if ( $has_actions_footer ) : ?>
		<div class="jm-notice__footer">
			<?php echo $actions_html; ?>
		</div>
	<?php endif; ?>
</div>
