#!/bin/sh
read -p "Please input your type ex: fix feature" type      # 提示使用者輸入
read -p "Please input your commit" commit      # 提示使用者輸入
git commit -m:"${type}: ${commit}"
npm run release
git push && git push --tags