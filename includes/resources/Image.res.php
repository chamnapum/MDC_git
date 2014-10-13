<?php
/*
 * ADOBE SYSTEMS INCORPORATED
 * Copyright 2007 Adobe Systems Incorporated
 * All Rights Reserved
 * 
 * NOTICE:  Adobe permits you to use, modify, and distribute this file in accordance with the 
 * terms of the Adobe license agreement accompanying it. If you have received this file from a 
 * source other than Adobe, then your use, modification, or distribution of it requires the prior 
 * written permission of Adobe.
 */

$res = array(
'PHP_IMAGE_NO_IMG' => 'Erreur de conversion de l\'image (changement de taille). Le fichier n\'existe pas.',
'PHP_IMAGE_READ_ERR' => 'Erreur de conversion de l\'image (changement de taille). Le fichier est illisible.',
'PHP_IMAGE_RESIZE_NO_LIB' => 'Erreur de conversion de l\'image (changement de taille).',
'PHP_IMAGE_RESIZE_ERR' => 'Erreur de conversion de l\'image (changement de taille). %s.',
'PHP_IMAGE_THUMBNAIL_NO_LIB' => 'Erreur &agrave; la conversion de l\'image (imagette).',
'PHP_IMAGE_THUMBNAIL_ERR' => 'Erreur &agrave; la conversion de l\'image (imagette). %s.',
'PHP_IMAGE_ADJUST_QUAL_NO_LIB' => 'Erreur de conversion de l\'image (ajustement de la qualit&eacute;).',
'PHP_IMAGE_ADJUST_QUAL_ERR' => 'Erreur de conversion de l\'image (ajustement de la qualit&eacute;). %s.',
'PHP_IMAGE_CROP_NO_LIB' => 'Erreur de conversion de l\'image (recadrage).',
'PHP_IMAGE_CROP_ERR' => 'Erreur de conversion de l\'image (recadrage). %s.',
'PHP_IMAGE_ROTATE_NO_LIB' => 'Erreur de conversion de l\'image (rotation).',
'PHP_IMAGE_ROTATE_ERR' => 'Erreur de conversion de l\'image (rotation). %s.',
'PHP_IMAGE_ROTATE_DEG_ERR' => 'Erreur de conversion de l\'image (rotation). Nombre de degr&eacute;s invalide.',
'PHP_IMAGE_FLIP_NO_LIB' => 'Erreur de conversion de l\'image (sym&eacute;trie). ImageMagick est requis.',
'PHP_IMAGE_FLIP_ERR' => 'Erreur de conversion de l\'image (sym&eacute;trie). %s.',
'PHP_IMAGE_SHARPEN_NO_LIB' => 'Erreur de conversion de l\'image (accentuation). ImageMagick est requis.',
'PHP_IMAGE_SHARPEN_ERR' => 'Erreur de conversion de l\'image (accentuation). %s.',
'PHP_IMAGE_BLUR_NO_LIB' => 'Erreur &agrave; la conversion de l\'image (flou). ImageMagick est exig&eacute;.',
'PHP_IMAGE_BLUR_ERR' => 'Erreur &agrave; la conversion de l\'image (flou). %s.',
'PHP_IMAGE_CONTRAST_NO_LIB' => 'Erreur &agrave; la conversion de l\'image (contraste). ImageMagick est exig&eacute;.',
'PHP_IMAGE_CONTRAST_ERR' => 'Erreur &agrave; la conversion de l\'image (contraste). %s.',
'PHP_IMAGE_BRIGHTNESS_NO_LIB' => 'Erreur &agrave; la conversion de l\'image (luminosit&eacute;). ImageMagick est exig&eacute;.',
'PHP_IMAGE_BRIGHTNESS_ERR' => 'Erreur &agrave; la conversion de l\'image (luminosit&eacute;). %s.',
'PHP_IMAGE_NO_IMG_ERR' => 'Erreur de conversion de l\'image (%s). Le fichier n\'existe pas.',
'PHP_IMAGE_INV_IMG' => 'Erreur de conversion de l\'image (%s). Fichier invalide.',
'PHP_IMAGE_CHECK_FOLDER_ERROR' => 'Erreur de conversion de l\'image (%s). Erreur interne.',
'PHP_IMAGE_FOLDER_ERROR' => 'Erreur de conversion de l\'image (%s). %s',
'PHP_IMAGE_GD_SUPPORT' => '(votre biblioth&egrave;que GD ne supporte pas : %s)',
'PHP_IMAGE_LIBRARY_SUPPORT' => '(La biblioth&egrave;que %s ne fonctionne pas ou elle est introuvable).',
'PHP_IMAGE_NO_IMG_D' => 'Error converting image (image size). File "%s" does not exist.',
'PHP_IMAGE_READ_ERR_D' => 'Error converting image (image size). File "%s" is not readable.',
'PHP_IMAGE_RESIZE_NO_LIB_D' => 'Error converting image (image resize). Image processing library not available or does not support operation. %s.',
'PHP_IMAGE_THUMBNAIL_ERR_D' => 'Error converting image (thumbnail). %s.',
'PHP_IMAGE_THUMBNAIL_NO_LIB_D' => 'Error converting image (thumbnail). Image processing library not available or does not support operation. %s.',
'PHP_IMAGE_RESIZE_ERR_D' => 'Error converting image (image resize). %s.',
'PHP_IMAGE_ADJUST_QUAL_NO_LIB_D' => 'Error converting image (adjust quality). ImageMagick or GD Library is required. %s.',
'PHP_IMAGE_ADJUST_QUAL_ERR_D' => 'Error converting image (adjust quality). %s.',
'PHP_IMAGE_CROP_NO_LIB_D' => 'Error converting image (crop). ImageMagick or GD Library is required. %s.',
'PHP_IMAGE_CROP_ERR_D' => 'Error converting image (crop). %s',
'PHP_IMAGE_ROTATE_NO_LIB_D' => 'Error converting image (rotate). ImageMagick or GD Library is required. %s.',
'PHP_IMAGE_ROTATE_ERR_D' => 'Error converting image (rotate). %s.',
'PHP_IMAGE_ROTATE_DEG_ERR_D' => 'Error converting image (rotate). Invalid degree number (valid values: 0, 90, 180, 270, 360).',
'PHP_IMAGE_FLIP_NO_LIB_D' => 'Error converting image (flip). ImageMagick is required.',
'PHP_IMAGE_FLIP_ERR_D' => 'Error converting image (flip). %s.',
'PHP_IMAGE_SHARPEN_NO_LIB_D' => 'Error converting image (sharpen). ImageMagick is required.',
'PHP_IMAGE_SHARPEN_ERR_D' => 'Error converting image (sharpen). %s.',
'PHP_IMAGE_BLUR_NO_LIB_D' => 'Error converting image (blur). ImageMagick is required.',
'PHP_IMAGE_BLUR_ERR_D' => 'Error converting image (blur). %s.',
'PHP_IMAGE_CONTRAST_NO_LIB_D' => 'Error converting image (contrast). ImageMagick is required.',
'PHP_IMAGE_CONTRAST_ERR_D' => 'Error converting image (contrast). %s.',
'PHP_IMAGE_BRIGHTNESS_NO_LIB_D' => 'Error converting image (brightness). ImageMagick is required.',
'PHP_IMAGE_BRIGHTNESS_ERR_D' => 'Error converting image (brightness). %s.',
'PHP_IMAGE_NO_IMG_ERR_D' => 'Error converting image (%s). The file "%s" does not exist.',
'PHP_IMAGE_INV_IMG_D' => 'Error converting image (%s). The file "%s" is invalid.',
'PHP_IMAGE_CHECK_FOLDER_ERROR_D' => 'Error converting image (%s). The "%s" folder has no %s permissions.',
'PHP_IMAGE_FOLDER_ERROR_D' => 'Error converting image (%s). %s',
'PHP_IMAGE_GD_SUPPORT_D' => '(your GD Library has no support for: %s)',
'PHP_IMAGE_LIBRARY_SUPPORT_D' => '(%s library is not working or not found)',
);
?>