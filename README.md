<p align="center"><img src="https://actcms.work/img/logo.svg" width="200px"></p>



## About Actcms Sharecode

Following changes to be made

- Keep the project in project-name folder (Example worksuite files in **worksuite** folder)

#### Outcome
- New version will be created in **versions** folder
- auto update will be created in **versions/auto-update/product-name** folder

## Installation

        composer require actcmsvn/sharecode
    
## Publish files
        php artisan vendor:publish --provider="Actcmsvn\Sharecode\ActcmsSharecodeServiceProvider"
    
## Command to run for creating new version
        php artisan script:version {version}

