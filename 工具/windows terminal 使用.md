https://printempw.github.io/windows-terminal-setup-guide/

https://docs.microsoft.com/zh-cn/windows/terminal/

https://sspai.com/post/59380



## 配置

ALT+点击setting打开default.json

官方文档

- [Editing Windows Terminal JSON Settings](https://github.com/microsoft/terminal/blob/master/doc/user-docs/UsingJsonSettings.md)
- [Profiles.json Documentation](https://github.com/microsoft/terminal/blob/master/doc/cascadia/SettingsSchema.md)

### 配置文件简介

```json
{
    // 默认打开的 Profile GUID（下面会详细介绍）
    "defaultProfile": "{e1e1ac58-02c1-456a-a857-01149673a65d}",
    // 终端窗口默认大小
    "initialCols": 120,
    "initialRows": 30,
    // 亮色或暗色主题，可选值 "light", "dark", "system"
    "requestedTheme": "system",
    // 合并标题栏和标签栏
    "showTabsInTitlebar": true,
    // 如果 showTabsInTitlebar 与本值同为 false 时，自动隐藏标签栏
    "alwaysShowTabs": true,
    // 在标题栏上显示当前活动标签页的标题
    "showTerminalTitleInTitlebar": true,
    // 双击选择时用于分词的字符
    "wordDelimiters": " /\\()\"'-.,:;<>~!@#$%^&*|+=[]{}~?\u2502",
    // 选择时复制到剪贴板
    "copyOnSelect": true,
    // 标签页宽度不固定
    "tabWidthMode": "titleLength",

    // ...
}
```



```json
"profiles": {
    "defaults": {
        // 所有 Profile 共用的设置可以放这里，就不用写多次了
        // 字体设置
        "fontFace": "Cascadia Code",
        "fontSize": 11,
        // 光标类型，可选值 "vintage" ( ▃ ), "bar" ( ┃ ), "underscore" ( ▁ ), "filledBox" ( █ ), "emptyBox" ( ▯ )
        "cursorShape": "underscore",
        // 背景亚克力透明效果（窗口失去焦点时无效）
        "useAcrylic": true,
        "acrylicOpacity": 0.8
    },
    "list": [
        {
            // 每个 Profile 的唯一标识符，生成方法见下
            "guid": "{e1e1ac58-02c1-456a-a857-01149673a65d}",
            // 设置为 true 即可在新建菜单中隐藏
            "hidden": false,
            // 名字，会显示在菜单中
            "name": "Ubuntu",
            // 启动命令行
            "commandline": "wsl.exe",
            // 启动目录
            "startingDirectory": ".",

            // 背景图片
            // "backgroundImage" : "X:\\path\\to\\background.png",
            // "backgroundImageOpacity" : 0.5,
            // "backgroundImageStretchMode" : "uniformToFill",

            // 菜单与标签中显示的图标
            "icon": "X:\\path\\to\\ubuntu.png",
            // 配色方案，见下
            "colorScheme": "Tango Dark",
            // 光标颜色
            "cursorColor": "#FFFFFF",

            // ... 其他配置请参见官方文档
        }
    ]
}
```



官方文档中推荐我们把自定义图标、背景图之类的放到与 `profiles.json` 同一目录里去，然后在配置中用 `ms-appdata:///Local/xxx` 的形式来引用资源。不过如果嫌麻烦的话，直接使用绝对路径也是没问题的



### 添加右键菜单



```
$basePath = "Registry::HKEY_CLASSES_ROOT\Directory\Background\shell"
New-Item -Path "$basePath\wt" -Force -Value "Windows Terminal here"
New-ItemProperty -Path "$basePath\wt" -Force -Name "Icon" -PropertyType ExpandString -Value "C:\Program Files\WindowsApps\Microsoft.WindowsTerminal_1.2.2381.0_x64__8wekyb3d8bbwe\Images\Square44x44Logo.targetsize-16.png"
New-Item -Path "$basePath\wt\command" -Force -Type ExpandString -Value '"%LOCALAPPDATA%\Microsoft\WindowsApps\wt.exe" -p Ubuntu -d "%V"'
```



## 快捷键







## PowerLine 无法正常显示

原因：Terminal 默认的字体是：Cascadia Mono，这个字体并不支持PowerLine

下载Cascadia Mono PL即可解决问题

https://github.com/microsoft/cascadia-code/releases

