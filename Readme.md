Added a new Job in mass action to add comments to list of simple products you have selected in products page.

Added a Menu Custom Settings inside main menu.

Added a Custom dashboard Inside activities tab we will list products in that page.

ACL(Access Control List) for Custom Dashboard And Custom Settings and shown in System menu and click on permissions tab and click on system there will be listing Custom Dashboard and Custom Settings

Shell script running commands in root folder:

sed -i '/new Sitation\\CustomRuleEngineBundle\\SitationCustomRuleEngineBundle(),/d' app/AppKernel.php;
sed -i '/your app bundles should be registered here/a new Sitation\\CustomRuleEngineBundle\\SitationCustomRuleEngineBundle(),' app/AppKernel.php;
composer config repositories.repo-name vcs https://github.com/wissenddevops/SitationCustomRuleEngineBundle.git; 
composer require "wissenddevops/SitationCustomRuleEngineBundle";
php bin/console sitation:addroutecustomrulesengine;
bin/console pim:install:assets;
yarn run less;
yarn run webpack;
bin/console cache:clear;