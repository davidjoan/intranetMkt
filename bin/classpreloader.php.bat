@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/classpreloader/classpreloader/classpreloader.php
php "%BIN_TARGET%" %*
