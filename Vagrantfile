
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.boot_timeout = 600

  config.vm.hostname = "elgg-multisite"
  config.vm.network :private_network, ip: "192.168.33.35"

  config.vm.synced_folder ".", "/home/vagrant/"

  config.vm.provision :shell, path: "vagrant/provision.sh"

end