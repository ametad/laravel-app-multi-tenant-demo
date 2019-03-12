# Demo multi tenant Laravel app

## Installation

Set your hosts file, for correct DNS resolution:

192.168.10.10 homestead.test  
192.168.10.10 foo.test   
192.168.10.10 bar.test  

### Run the following commands on your host machine:

*  `vagrant box add laravel/homestead` <br>https://laravel.com/docs/5.8/homestead#installation-and-setup
*  `composer install` <br>https://getcomposer.org/
*  `php vendor/bin/homestead make` <br>https://laravel.com/docs/5.8/homestead#per-project-installation
*  (Edit Homestead.yaml file)
*  `vagrant up` <br>https://www.vagrantup.com/ <br> https://www.virtualbox.org/
*  `vagrant ssh`
*  `cp .env.example .env`
*  `php artisan key:generate`
*  (Edit .env file)

### Run the next commands inside the vm:

*  `cd code`
*  `artisan migrate --database=system`
*  `artisan website:create`
*  `artisan hostname:create --website_id=1 foo.test`
*  `artisan hostname:create --website_id=1 bar.test`

**Note** the artisan `website` and `hostname` commands are not from the hyn/multi-tenant package.

[![asciicast](https://asciinema.org/a/jStpBaiWAO1apY86SjCJ9UDuz.svg)](https://asciinema.org/a/jStpBaiWAO1apY86SjCJ9UDuz)

## Example Homestead.yaml

```yaml
ip: 192.168.10.10
memory: 2048
cpus: 1
provider: virtualbox
authorize: ~/.ssh/id_rsa.pub
keys:
    - ~/.ssh/id_rsa
folders:
    -
        map: /home/user/laravel-app-multi-tenant-demo
        to: /home/vagrant/code
sites:
    -
        map: homestead.test
        to: /home/vagrant/code/public
    -
        map: foo.test
        to: /home/vagrant/code/public
    -
        map: bar.test
        to: /home/vagrant/code/public
databases:
    - homestead
name: laravel-app-multi-tenant-demo
hostname: laravel-app-multi-tenant-demo
```