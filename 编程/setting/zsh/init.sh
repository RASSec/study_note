#!/bin/bash
sudo apt-get install zsh
sh -c "$(wget -O- https://gitee.com/explorersss/ohmyzsh/raw/master/tools/install.sh)"
rm .zshrc && wget https://raw.githubusercontent.com/Explorersss/study_note/master/%E7%BC%96%E7%A8%8B/setting/zsh/.zshrc
git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting