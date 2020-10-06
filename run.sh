#!/bin/sh
read -p "Please input your type ex fix feature: " type      # 提示使用者輸入
read -p "Please input your commit: " commit      # 提示使用者輸入
printf "將檔案加上commit"
git commit -m:"${type}: ${commit}"
printf "將package 更新版本號"
npm run release
printf "將檔案推至github上"
git push && git push --tags