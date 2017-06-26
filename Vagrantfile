Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial32"
  config.vm.box_check_update = false
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.network "private_network", ip: "192.168.33.11"
  config.vm.synced_folder ".", "/var/www"

  config.vm.provider "virtualbox" do |vb|
     vb.memory = "1024"
     vb.gui = false
  end

  config.vm.provision "shell", inline: <<-SHELL
    echo "Please do 'vagrant ssh', switch to '/var/www/.setup' and then do 'sudo ./setup.sh' to install all dependencies."
  SHELL
end
