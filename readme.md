## Spectre Tech Test

By Jonathan Marshall on 24th September 2019.  
It is a standard Laravel repository and can be hosted anywhere you would usually host a Laravel application.  

I have provided a Vagrant virtual machine which can host the application locally for you.  
The instructions are aimed at OSX machines.  

If you have used Vagrant with VirtualBox before, you can ignore the majority of this and skip right to the 'vagrant up' part.

---
OSX Setup
---

Install the Command Line Utilities by opening a Terminal window and running `git`.

Then follow the instructions to install the Command Line Utilities.

Install Vagrant: https://www.vagrantup.com/downloads.html  
Install VirtualBox: https://www.virtualbox.org/wiki/Downloads

Generate an ssh-key:  
`ssh-keygen -t rsa -b 4096 -C “you@email.co.uk”`

Add your ssh key to your GitHub account:  
https://help.github.com/articles/adding-a-new-ssh-key-to-your-github-account/

Install home-brew:  
https://brew.sh/

Install ruby ring brew:  
`brew install ruby`
 
Clone the repo:  
`git clone git@github.com:opsjono/spectre`

Add these lines to the bottom of the /etc/sudoers file using `sudo visudo` to enable password-less 'vagrant up'
```
Cmnd_Alias VAGRANT_EXPORTS_ADD = /usr/bin/tee -a /etc/exports
Cmnd_Alias VAGRANT_NFSD = /sbin/nfsd restart
Cmnd_Alias VAGRANT_EXPORTS_REMOVE = /usr/bin/sed -E -e /*/ d -ibak /etc/exports
%admin ALL=(root) NOPASSWD: VAGRANT_EXPORTS_ADD, VAGRANT_NFSD, VAGRANT_EXPORTS_REMOVE
```

OSX-vagrant-vbguest required a specific version of nokogiri. To install it run:  
`sudo gem install nokogiri -v “1.6.6.2”`

Install virtualbox guest additions to enable file sharing:  
`vagrant plugin install vagrant-vbguest`

Spin up the virtual machine (this may take 10-15 minutes)  
`vagrant up`

Edit local hosts: `vim /etc/hosts` add this line to the bottom:
```
192.168.77.77 spectre.marshall.vm
```

SSH into the VM and generate the application key:
- `vagrant ssh`
- `cd /vagrant`
- `cp .env.example .env`
- `php artisan key:generate`

Open http://spectre.marshall.vm in your browser.  