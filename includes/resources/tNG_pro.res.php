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
'BADWORDS_SQL_ERROR' => 'Erreur de lecture de la liste des mots incorrects dans la base de donn&eacute;es!',
'BADWORDS_SQL_ERROR_D' => 'Error reading bad word list from database! %s, %s',
'BADWORDS_FILE_ERROR' => 'Erreur de lecture de la liste des mots incorrects &agrave; partir du fichier.',
'BADWORDS_FILE_ERROR_D' => 'Error reading bad words from file: %s',
'FOLDER_DEL_ERROR' => 'Erreur en supprimant le dossier. L\'erreur serveur est : %s',
'FOLDER_DEL_ERROR_D' => 'Error deleting folder. Server error is: %s',
'FORBIDDEN_FIELD_ERROR' => 'Champ ayant des mots interdits.',
'FORBIDDEN_FIELD_ERROR_D' => '',
'LOGIN_LOGGER_ERROR' => 'Une erreur s\'est produite en s\'identifiant.',
'LOGIN_LOGGER_ERROR_D' => 'An error occured when saving log information: %s, %s',
'LOGIN_MESSAGE__MAXTRIES' => 'Le compte a &eacute;t&eacute; neutralis&eacute; parce que vous avez atteint le maximum autoris&eacute; de tentatives d\'identification.',
'LOGIN_MESSAGE__MAXTRIES_D' => '',
'LOGIN_MESSAGE__ACCOUNT_EXPIRE' => 'Votre compte a expir&eacute;.',
'LOGIN_MESSAGE__MAXTRIES_DENIED' => 'Le compte a &eacute;t&eacute; temporairement neutralis&eacute; (durant %s minutes) parce que vous avez atteint le maximum autoris&eacute; de tentatives d\'identification (%s)',
'LOGIN_MESSAGE__MAXTRIES_DENIED_D' => '',
'LOGIN_MESSAGE__MAXTRIES_DENIED_PERMANENT' => 'Le compte a &eacute;t&eacute; neutralis&eacute; de mani&egrave;re permanente (durant %s minutes) parce que vous avez atteint le maximum autoris&eacute; de tentatives d\'identification (%s)',
'LOGIN_MESSAGE__MAXTRIES_DENIED_PERMANENT_D' => '',
'LOGIN_MESSAGE__MAXTRIES_ERROR_D' => 'Error executing the SQL %s, %s',
'TRIGGER_MESSAGE__CHECK_FORBIDDEN_WORDS' => 'Mots interdits trouv&eacute;s pour le(s) champ(s) \'%s\'.',
'ERR_DOWNLOAD_FILE_D' => 'Error downloading file! %s, %s',
'LOGIN_MESSAGE__MAXTRIES_ERROR_D' => 'Error executing the SQL %s, %s',
'LOGIN_MESSAGE__EXP_ACCOUNT_ERROR' => 'Erreur en ex&eacute;cutant la commande SQL',
'LOGIN_MESSAGE__EXP_ACCOUNT_ERROR_D' => 'Error executing the SQL %s, %s',
'LOGIN_MESSAGE__EXP_ACCOUNT' => 'Votre compte a expir&eacute; !',
'LOGIN_MESSAGE__EXP_ACCOUNT_D' => '',
'INCREMENTER_ERROR' => 'Erreur en incr&eacute;mentant le champ compteur !',
'INCREMENTER_ERROR_D' => 'Error incrementing the counter field! %s, %s',
'INCREMENTER_ERROR_FK' => 'Vous ne pouvez t&eacute;l&eacute;charger aucun fichier si vous n\'&ecirc;tes pas identifi&eacute; !',
'INCREMENTER_ERROR_FK_D' => 'You cannot use the Download Files behavior if you are not logged in!<br/>WARNING: You should apply a Restrict Access to Page behavior on all files where you use Download Files with limit per user! %s',
'INCREMENTER_ERROR_PK' => 'Erreur en t&eacute;l&eacute;chargeant le fichier ! La clef primaire n\'a aucune valeur.',
'INCREMENTER_ERROR_PK_D' => 'Error downloading file! Primary key has no value! %s',
'CHECK_COUNTER_ERROR' => 'Erreur en incr&eacute;mentant le compteur de t&eacute;l&eacute;chargement ! %s',
'CHECK_COUNTER_ERROR_D' => 'Error incrementing the download counter! %s',
'CHECK_COUNTER_ERROR_MAX' => 'Vous avez atteint le nombre maximum de t&eacute;l&eacute;chargements %s',
'CHECK_COUNTER_ERROR_MAX_D' => '',
'TRIGGER_MESSAGE__CHECK_FORBIDDEN_WORDS_D' => '',
'EMAIL_ERROR_FOLDER' => 'Une erreur s\'est produite en lisant le dossier avec l\'attachement.',
'EMAIL_ERROR_FOLDER_D' => 'An error occured when reading the folder with the attachment %s, %s.',
'MAX_FILES_NO_REACHED' => 'Le nombre maximum des fichiers a &eacute;t&eacute; atteint (%s)',
'FOLDER_DEL_SECURITY_ERROR' => 'Erreur du dossier. Erreur de S&eacute;curit&eacute;.',
'FOLDER_DEL_SECURITY_ERROR_D' => 'Folder error. Security Error. Folder \'%s\' is out of base folder \'%s\'.',
'FLASH_MAX_SIZE_REACHED' => 'Fichier %s ignor&eacute;. La taille de ce fichier est de %s kB, et la taille maximale autoris&eacute;e est de %s kB.',
'FLASH_MAX_FILES_REACHED' => 'Fichier %s ignor&eacute;. Vous ne pouvez pas t&eacute;l&eacute;charger plus de %s fichiers.',
'FLASH_EMPTY_FILE' => 'Fichier %s ignor&eacute;. Ce fichier est vide. Vous ne pouvez pas t&eacute;l&eacute;charger de fichiers vides.',
'DELETE' => 'Supprimer',
'UPLOAD' => 'T&eacute;l&eacute;charger',
'CLOSE' => 'Fermer la fen&ecirc;tre',
'FILES' => 'Fichiers',
'MAXFILES' => 'du max',
'FLASH_SKIPPING' => 'Ignorer le fichier',
'FLASH_HTTPERROR' => 'Erreur de communication avec le serveur pour envoyer : %s. Erreur: %s.',
'FLASH_HTTPERROR_HEAD' => 'Erreur HTTP',
'FLASH_IOERROR' => 'Erreur de lecture/&eacute;criture : %s.',
'FLASH_IOERROR_HEAD' => 'Erreur d\'Entr&eacute;e/Sortie',
'FLASH_COMPLETE_MSG' => 'Fin du t&eacute;l&eacute;chargement flash. Si le navigateur ne se rafra&icirc;chi pas automatiquement, cliquer sur le bouton rafra&icirc;chir',
'FLASH_UPLOAD_BATCH' => 'T&eacute;l&eacute;charg&eacute; %s sur %s',
'FLASH_UPLOAD_SINGLE' => 'T&eacute;l&eacute;chargement du fichier',
'CLICK_ENLARGE' => 'Cliquer pour agrandir',
'EMPTY_MUP_POPUP' => 'Il n\'y a aucun fichier t&eacute;l&eacute;charg&eacute; sur le serveur.<br /> Cliquer sur le bouton T&eacute;l&eacute;charger pour s&eacute;lectionner les fichiers.',
);
?>