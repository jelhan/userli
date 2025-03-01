# 2.3.1 (UNRELEASED)

* Skip check against HIBP service if unavailable

# 2.3.0 (2019.07.16)

* Add manual language switcher (Fixes: #172)
* Add Norwegian Bokmål as available translation
* Block Spammers from authenticating via checkpassword (Fixes: #177)
* Test passwords againt haveibeenpwned database (Fixes: #161)
* Upgrade to symfony 4.3.2
* Improve speed of Vagrant box

# 2.2.3 (2019.06.24)

* Repair js copying of invite codes (Fixes: #165)
* Several minor language fixes (Thanks to ssantos)
* Start Norwegian translation (Thanks to Allan Nordhøy)
* Switch to PHP-only checkpassword script for security reasons. This
  eliminates the requirement to setup sudo. See the updated docs for
  details.

# 2.2.2 (2019.06.14)

* Delete aliases when deleting user (Fixes: #121)
* Fix error when trying to register deleted username (Fixes: #176)
* Remove link to registration from right navbar
* Update PHP and JS dependecies

# 2.2.1 (2019.06.06)

* Add org/organisation/... to reserved names
* Update to symfony 4.2.9
* Update PHP and JS dependecies
* Rename ROLE_SUPPORT to ROLE_MULTIPLIER

# 2.2.0 (2019.05.22)

* Add initial Spanish translation
* Add initial Portuguese translation (Thanks to Bruno Gama)
* Add plural forms of many reserved names
* Update to symfony 4.2.8
* Fix mailcrypt-encrypt-maildir script for paths with whitespaces
* Fix release tarball creation, don't use tar option --transform

# 2.1.2 (2019.04.18)

* Create release tarball in subdirectory
* Add optional link to webmail (Fixes: #146)
* Update to symfony 4.2.7

# 2.1.1 (2019.03.17)

* Change default locale setting to 'en'
* Don't resolve symlinks to not break sudo in checkpassword

# 2.1.0 (2019.03.17)

* New shell script `bin/mailcrypt-encrypt-maildir` to encrypt legacy mailboxes
* Update to symfony 4.2.4
* Add sudo support to checkpassword script (Fixes: #127)
* Update SecurityController to use AuthenticationUtils
* Add CSRF protection to login forms (Fixes: #95)

# 2.0.2 (2019.03.06)

* Add column and filter for `hasRecoveryToken` property on user in admin list (Fixes: #144)
* Export number of users with Recovery Tokens to Munin
* Recovery also works now with email localpart (Fixes: #148)
* Fix release tar balls (Fixes: #150)

# 2.0.1 (2019.03.04)

* We adopted the code of conduct from Contributor Covenant
* Fix bug in `CryptoSecretHandler::decrypt()` that broke recovery token recreation.

# 2.0.0 (2019.02.23)

* Rename project to Userli (Fixes: #133)
* Add support for Dovecot's MailCrypt plugin. New users automatically get
  a MailCrypt key pair generated which is then passed to Dovecot via
  `checkpassword`. (Fixes: #83)
* Add support for recovery tokens. New users automatically get a recovery
  token generated, which can be used to restore their account if the
  password got lost at a later time. (Fixes: #89, #106, #108)
* Add support for direct registration links with prefilled invite vouchers.
  (Fixes: #117)
* Move flash messages to upper right corner (Fixes: #129)
* Always display footer when logged in (Fixes: #104)
* Open external links in new window (Fixes: #100)
* Add option to copy link as URL (Fixes: #117)
* Explain purpose of alias addresses (Fixes: #45)
* Remove trailing slash from default URLs
* Adjust database to snake_case. See `UPGRADE.md` on how to adjust an older
  database. (Fixes: #112)
* Add infobox with password policy to password change forms (Fixes: #130)
* Turn autocompletion off for voucher form field at registration (Fixes: #32)
* Started external docs at [systemli.github.io/userli](https://systemli.github.io/userli)

# 1.6.2 (2019.02.08)

* Update to symfony 4.1.11
* Hide vouchers in secondary domains (Fixes: #110)
* DomainAdmins are only allowed to create Alias in their domain (Fixes: #94)

# 1.6.1 (2019.01.14)

* Update to symfony 4.1.10
* Add quota to checkpassword (Fixes: #91)

# 1.6.0 (2019.01.04)

* Add a role for detected spammers (Fixes: #77)
* Split startpage into subpages (Fixes: #43)
* Reverse order of vouchers, display newest vouchers first
* Fix when users updatedTime is updated (Fixes: #71)
* Don't show voucher menu to suspicious users (Fixes: #81)

# 1.5.3 (2018.12.13)

* Add scripts to automate building and publishing of releases

# 1.5.2 (2018.12.07)

* Start to list relevant changes in a dedicated changelog file.
* Hide voucher stats from domain admins (Fixes: #72)
* Improve message about custom alias limit (Fixes: #74)

# 1.5.1 (2018.11.28)

* Fix passing passwords that start with special chars to checkpassword script
