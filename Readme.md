rules:
    ImageResize:
        priority: 0
        conditions:
            - {field: family, operator: IN, value: [tops]}
        actions:
            - {type: imageoperation, field: image_2, attributes: [image_1],imageoperation:resize,width:500,height:500,imageextension:png,options: {scope: ~, locale: ~}}
use above rules engine configuration place it in one file and import that file in akeneo dev box using this import function "ImportToYaml" and rules engine get created.

before that please use below script to be run use it in root folder of project.


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