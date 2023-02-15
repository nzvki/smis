#!/bin/bash

# Sync registration documents
/usr/bin/php /var/www/html/smis/yii student-registration/documents/sync;

# Sync student profile updates
/usr/bin/php /var/www/html/smis/yii student-registration/profile/sync;

# Sync session reporting dates
/usr/bin/php /var/www/html/smis/yii student-registration/session-reporting/sync;
