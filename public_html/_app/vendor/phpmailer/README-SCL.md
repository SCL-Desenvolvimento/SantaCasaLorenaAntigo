# PHPMailer vendored dependency

Version: 7.1.1. Source: https://github.com/PHPMailer/PHPMailer/tree/v7.1.1
License: LGPL-2.1; see LICENSE.

Unmodified upstream src/PHPMailer.php, src/SMTP.php and src/Exception.php are loaded
explicitly by includes/community_mailer.php. This installation does not use Composer.
Only the modern public contact and donation forms use this namespaced version.
The legacy administrative mailer is not replaced by this scoped change.
To update, retrieve these files and LICENSE from the same official release tag,
then run tests/community.php, including MIME generation, before deployment.
