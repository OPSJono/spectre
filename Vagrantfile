# -*- mode: ruby -*-
# vi: set ft=ruby :

# This didn't work in windows on version 1.8.1
# 1.9.1 was fine
Vagrant.require_version ">1.8.1"

##
# If vagrant-vbguest fails to install on a mac, check out this link: https://blog.mdnsolutions.com/vagrant-up-in-mac-os-vboxsf-file-system-is-not-available/
##

def getConfigurationValue(setting)
    if File.exist?("./vmConfigOverrides/" + setting)
       return File.read("./vmConfigOverrides/" + setting).strip
    end
    return File.read("./vmConfigDefaults/" + setting).strip
end

vmconfigFile_vboxName = getConfigurationValue("virtualBoxName")
vmconfigFile_ipAddress = getConfigurationValue("ipAddress")
vmconfigFile_hostName = getConfigurationValue("hostName")
vmconfigFile_memory = getConfigurationValue("memory")
vmconfigFile_cpus = getConfigurationValue("cpus")

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|

  # Prefer libvirt over virtualbox
  config.vm.provider "libvirt"
  config.vm.provider "virtualbox"

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  # We're using yk0/ubuntu-xenial because it supports both Libvirt and VirtualBox providers
  config.vm.box_url = "https://vagrantcloud.com/yk0/ubuntu-xenial/"
  config.vm.box = "yk0/ubuntu-xenial"
  config.vm.hostname = vmconfigFile_hostName

  config.vm.define :spectre do |spectre|
    spectre.vm.network :private_network, :ip => vmconfigFile_ipAddress
  end

  # Libvirt configuration options
  config.vm.provider :libvirt do |libvirt, override|
    override.vm.synced_folder "./","/vagrant", id:"vagrant", type: "nfs", mount_options: ["rw", "tcp", "nolock", "noacl", "async"], nfs_udp: false

    libvirt.default_prefix = ''
    libvirt.cpu_mode = "host-passthrough"
    libvirt.uri = 'qemu+unix:///system'
    libvirt.host = vmconfigFile_hostName
    libvirt.cpus = vmconfigFile_cpus
    libvirt.memory = vmconfigFile_memory
  end

  # VirtualBox configuration options
  config.vm.provider :virtualbox do |vb, override|
    override.vm.synced_folder "./","/vagrant", id:"vagrant", type: "virtualbox"
    
    # Set the name of the virtual machine in the provider
    vb.name = vmconfigFile_vboxName
    # Display the VirtualBox GUI when booting the machine
    vb.gui = false
    # Customize the amount of memory on the VM:
    vb.memory = vmconfigFile_memory
    vb.cpus = vmconfigFile_cpus

##
## https://joeshaw.org/terrible-vagrant-virtualbox-performance-on-mac-os-x/
#    vb.customize [
#      "storagectl", :id,
#      "--name", "SATA Controller",
#      "--hostiocache", "on"
#    ]
##
##
  end

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: vmconfigFile_ipAddress

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  #config.vm.provision "shell", inline: <<-SHELL
  #  apt-get update
  #  apt-get install -y ansible
  # apt-get install -y apache2
  #SHELL
  if Vagrant::Util::Platform.linux?
    config.vm.provision :ansible do |ansible|
        ansible.playbook = "vagrant/playbooks/_provision_vm.yml"
        #ansible.verbose = "v"
        ansible.limit = "all"
    end
  else
    config.vm.provision "ansible_local" do |ansible|
        ansible.playbook = "vagrant/playbooks/_provision_vm.yml"
        ansible.limit = "all"
    end
  end
end


#### Notes for Linux users:
# Ansible 2.4.x or greater required: (must be installed via pip)
# sudo apt-get install python-pip
# sudo pip install ansible
# sudo apt-get build-dep vagrant ruby-libvirt
# sudo apt-get install -y qemu qemu-kvm libvirt-bin ebtables dnsmasq nfs-common
# sudo apt-get install -y libxslt-dev libxml2-dev libvirt-dev zlib1g-dev ruby-dev
# vagrant plugin install vagrant-libvirt
# vagrant up
# vagrant ssh
# cd ad-hoc
# time zcat emsrlt-2018-01-11.sql.gz | mysql -u root -preverse emsrlt && time zcat setpwd.sql.gz | mysql -u root -preverse emsrlt