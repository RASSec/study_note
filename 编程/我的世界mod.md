# 我的世界Mod制作学习

 http://blog.hakugyokurou.net/?p=134 

基本是抄 https://fmltutor.ustc-zzzz.net/ ,就是为了给自己看用的

## 基础部分

### 开发环境配置

1. 下载 Forge**Mdk** 

http://files.minecraftforge.net/maven/net/minecraftforge/forge/1.8.9-11.15.1.2318-1.8.9/forge-1.8.9-11.15.1.2318-1.8.9-mdk.zip

2. 安装

   ```
   ./gradlew setupDecompWorkspace 
   #加入-DsocksProxyHost=<host> -DsocksProxyPort=<port>来启用socks代理
   ```

   

### 运行、构建和发布Mod的方法

```shell
./gradlew eclipse#为eclipse生成工程文件
./gradlew idea && ./gradlew genIntellijRuns#为idea生成工程文件及配置
./gradlew build#编译
./gradlew runClient#运行客户端
./gradlew runServer#运行服务端

```



### modid:mod的唯一标识符

每个Mod都会有一串唯一标识符用于与其他Mod相区分，这个标识符我们通常也称为modid。

**modid请全部使用小写字母，并且不要使用除英文字母和下划线外的其他符号**。

###  bulid.gradle 修改

```shell
version = "1.0"
group= "com.yourname.yourname"#命名方式:网站名.yourname.yourname
archivesBaseName = "modid"#命名方式:名称+横线+版本号.jar
```

gradle的详细配置文档: https://docs.gradle.org/current/userguide/userguide.html 

版本命名推荐规则: https://semver.org/lang/zh-CN/ 

### mod模板及解释

 新建一个包（这里是`com.github.ustc_zzzz.fmltutor`），并在其中新建一个类（**强烈建议这个类的类名和你的Mod名称相同**），这就是这个Mod的主类了。 

 新建一个包`com.github.ustc_zzzz.fmltutor.common`，在其中新建一个类`CommonProxy` 

新建包`com.github.ustc_zzzz.fmltutor.client`，新建类`ClientProxy`，并继承类`CommonProxy`：

FMLTutor.java

```java
package com.github.ustc_zzzz.fmltutor;

import com.github.ustc_zzzz.fmltutor.common.CommonProxy;

import net.minecraftforge.fml.common.Mod;
import net.minecraftforge.fml.common.Mod.EventHandler;
import net.minecraftforge.fml.common.Mod.Instance;
import net.minecraftforge.fml.common.SidedProxy;
import net.minecraftforge.fml.common.event.FMLInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPostInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;

/**
 * @author ustc_zzzz
 */
@Mod(modid = FMLTutor.MODID, name = FMLTutor.NAME, version = FMLTutor.VERSION, acceptedMinecraftVersions = "1.8.9")
public class FMLTutor
{
    public static final String MODID = "fmltutor";
    public static final String NAME = "FML Tutor";
    public static final String VERSION = "1.0.0";
    @SidedProxy(clientSide = "com.github.ustc_zzzz.fmltutor.client.ClientProxy", 
                serverSide = "com.github.ustc_zzzz.fmltutor.common.CommonProxy")
    public static CommonProxy proxy;

    @Instance(FMLTutor.MODID)
    public static FMLTutor instance;

    @EventHandler
    public void preInit(FMLPreInitializationEvent event)
    {
        proxy.preInit(event);
    }

    @EventHandler
    public void init(FMLInitializationEvent event)
    {
        proxy.init(event);
    }

    @EventHandler
    public void postInit(FMLPostInitializationEvent event)
    {
        proxy.postInit(event);
    }
}
```

 **`CommonProxy.java`** 

```java
package com.github.ustc_zzzz.fmltutor.common;

import net.minecraftforge.fml.common.event.FMLInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPostInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;

public class CommonProxy
{
    public void preInit(FMLPreInitializationEvent event)
    {

    }

    public void init(FMLInitializationEvent event)
    {

    }

    public void postInit(FMLPostInitializationEvent event)
    {

    }
}
```

 ClientProxy.java

```java
package com.github.ustc_zzzz.fmltutor.client;

import com.github.ustc_zzzz.fmltutor.common.CommonProxy;

import net.minecraftforge.fml.common.event.FMLInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPostInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;


public class ClientProxy extends CommonProxy
{
    @Override
    public void preInit(FMLPreInitializationEvent event)
    {
        super.preInit(event);
    }

    @Override
    public void init(FMLInitializationEvent event)
    {
        super.init(event);
    }

    @Override
    public void postInit(FMLPostInitializationEvent event)
    {
        super.postInit(event);
    }
}
```









#### @Mod

FML在加载这个Mod的时候，就会去自动寻找含有`@Mod`注解的类，并读取下面的数据：

- `modid`指的就是该Mod的唯一标识符
- `name`指的是该Mod的名称
- `version`指的是该Mod的版本号，在Mod间的依赖关系时可能会用作识别
- `acceptedMinecraftVersions`指的是Mod接受的Minecraft版本，当版本不对时，FML会优雅地抛出一个错误而不是继续加载这个Mod

`acceptedMinecraftVersions`约定的版本声明如下：

- `1.8.9`（本教程）表示该Mod只支持Minecraft 1.8.9
- `[1.8,1.9)`表示该Mod支持从1.8（包含）到1.9（不包含）的所有Minecraft版本
- `[1.8,1.10]`表示该Mod支持从1.8（包含）到1.10（包含）的所有Minecraft版本
- `[1.8,)`表示该Mod支持从1.8（包含）之后出现的所有Minecraft版本
- `(,1.8],[1.9,)`表示该Mod支持1.8（包含）之前出现的所有Minecraft版本和从1.9（包含）之后出现的所有Minecraft版本

#### @EventHandler

下面的三个方法带有`@EventHandler`注解，它们的作用也是类似。Forge在找到这个类后，会检查这个类中所有含有`@EventHandler`注解的方法，并通过方法的参数类型来判定到底应该在何时调用它们：

- 含有`FMLPreInitializationEvent`参数的方法（这里是`preInit`）在所有Mod初始化之前调用，**这时候应该加载配置文件，实例化物品和方块，并注册它们**。
- 含有`FMLInitializationEvent`参数的方法（这里是`init`）用于该Mod的初始化，**这时候应该为Mod进行设置，如注册合成表和烧炼系统，并且向其他Mod发送交互信息**。
- 含有`FMLPostInitializationEvent`参数的方法（这里是`postInit`）在所有Mod都初始化之后调用，**这时候应该接收其他Mod发送的交互信息，并完成对Mod的设置**。

有些Mod会把注册方块、物品等等操作放在Mod初始化阶段完成，**这种做法是不推荐的，Forge推荐在`preInit`阶段完成**。

####  @Instance



 `@Instance`注解的作用是将生成的该Mod的实例，注册到对应的Mod的id，同时，也可以访问其他Mod的id对应的实例，当然，**这里的id要和本Mod的id相同**。 

#### 代理

>众所周知，Minecraft Mod有客户端和服务端两种使用方式，而两种方式的差异足够大使得Mod需要采用两种初始化方式，而两种方式的差异又足够小使得Mod没有必要制作客户端和服务端两个版本。这时候代理便起到了区别两种初始化方式的作用。**在单机运行时，Minecraft也会生成一个本地服务端**。服务端和客户端之间的差异十分复杂，甚至很多都只是经验之谈，然而有一点往往是通用的，**服务端的代码，往往客户端都会执行**。

 当服务端被初始化时，`CommonProxy`类中对应方法会被调用，如果是客户端，`ClientProxy`类中对应方法会被调用，这样我们就可以实现服务端和客户端的差异。 

###  mcmod.info : mod信息

 一个Mod的信息在其jar根目录下的`mcmod.info`文件里，这里是`src/main/resources/mcmod.info`，打开就可以完善你的Mod信息。**注意：`version`和`mcversion`字段不应修改，它们会在Gradle构建Mod的时候被自动替换掉**。你应该更改`build.gradle`文件。 

例子:

```
[
{
  "modid": "fmltutor",
  "name": "FML Tutor",
  "description": "A Minecraft 1.8 Forge Mod Loader Tutorial by ustc_zzzz.",
  "version": "${version}",
  "mcversion": "${mcversion}",
  "url": "https://github.com/ustc-zzzz/fmltutor/wiki",
  "updateUrl": "https://github.com/ustc-zzzz/fmltutor/tags",
  "authorList": ["ustc_zzzz"],
  "credits": "Notch, Cpw, etc.",
  "logoFile": "",
  "screenshots": [],
  "dependencies": []
}
]
```



### 制作物品

制作一个物品一共分三步：

1. 创建一个物品
2. 实例化并注册这个物品
3. 为这个物品添加模型和材质

#### 创建一个物品

新建一个包`com.github.ustc_zzzz.fmltutor.item`，在其中创建一个类`ItemGoldenEgg`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemGoldenEgg.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import net.minecraft.item.Item;

public class ItemGoldenEgg extends Item
{
    public ItemGoldenEgg()
    {
        super();
        this.setUnlocalizedName("goldenEgg");
    }
}
```

这里的`setUnlocalizedName`方法为该物品添加了一个非本地化的名称，该名称为“`item.`”+设置的名称，比如这里就是`item.goldenEgg`，这个非本地化名称，与本地化和国际化有关，在后面的部分我们会讲到。**非本地化名称尽量使用小写驼峰式写法，即第一个词以小写字母开始，第二个词开始首字母大写，中间不使用任何符号分隔**。

#### 实例化并注册这个物品

在`CommonProxy`类中添加下面的代码：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void preInit(FMLPreInitializationEvent event)
    {
        new ItemLoader(event);
    }
```

新建一个类`ItemLoader`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import net.minecraft.item.Item;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;
import net.minecraftforge.fml.common.registry.GameRegistry;

public class ItemLoader
{
    public static Item goldenEgg = new ItemGoldenEgg();//实例化物品

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
    }

    private static void register(Item item, String name)
    {
        GameRegistry.registerItem(item.setRegistryName(name));//注册这个物品
    }
}
```

##### 代码解释



首先，我们要实例化这个物品：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java（部分）:`**

```java
    public static Item goldenEgg = new ItemGoldenEgg();
```

然后，我们来到了这块的重点，注册这个物品：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java（部分）:`**

```java
        GameRegistry.registerItem(item.setRegistryName(name));
```

 `GameRegistry`是Forge提供的一个用来注册物品、方块、合成表、烧炼规则等各种常见内容的类 ,其常用的类方法

- `registerBlock`方法用于注册方块
- `registerFuelHandler`方法用于注册燃料
- `registerItem`方法用于注册物品
- `registerTileEntity`方法用于注册TileEntity（后面会讲到什么是TileEntity）
- `registerWorldGenerator`方法用于注册世界生成器以生成不同的世界
- `addRecipe`方法和`addShapedRecipe`方法用于注册合成表
- `addSmelting`方法用于注册物品烧炼规则

 这个方法需要传入一个`Item`类的实例用于注册物品，那么如何指定这个物品的id呢？在示例中，我们通过调用物品的`setRegistryName`方法指定了物品的id

 我们这里通过参数提供物品的id。**id请尽量使用小写字母加下划线，并且同一个Mod下的物品id不能相同**，有的Mod会使用驼峰式，这样的好处是把物品的非本地化名称和物品id设置成相同的，**但是我们不推荐这样的做法**。 

#### 为这个物品添加模型和材质

新建一个文件夹：`src/main/resources/assets/fmltutor/models/item`，并在其中新建一个文件：`golden_egg.json`：

**`src/main/resources/assets/fmltutor/models/item/golden_egg.json:`**

```json
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/golden_egg"
    },
    "display": {
        "thirdperson": {
            "rotation": [ -90, 0, 0 ],
            "translation": [ 0, 1, -2 ],
            "scale": [ 0.55, 0.55, 0.55 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

当然，这里的`fmltutor`就是Mod id，`golden_egg`就是你的物品id。

 这个json的文件，就是这个物品的模型 

**`src/main/resources/assets/fmltutor/models/item/golden_egg.json（部分）:`**

```json
    "textures": {
        "layer0": "fmltutor:items/golden_egg"
    },
```

这一部分告诉我们的是这个物品材质的位置，也就是`fmltutor:items/golden_egg`，很明显，我们需要建立一个材质文件。这里使用的是16x16的材质文件（当然Minecraft也支持尺寸更大如32x32的材质文件，不过建议还是使用16x16的），新建文件夹`src/main/resources/assets/fmltutor/textures/items`，把制作完成的golden*egg.png放入

 所有模型和材质都准备好了，现在需要做的，就是**让Minecraft知道你准备的模型和材质**了。 

修改`ItemLoader`类的内容：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import net.minecraft.client.resources.model.ModelResourceLocation;
import net.minecraft.item.Item;
import net.minecraftforge.client.model.ModelLoader;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;
import net.minecraftforge.fml.common.registry.GameRegistry;
import net.minecraftforge.fml.relauncher.Side;
import net.minecraftforge.fml.relauncher.SideOnly;

public class ItemLoader
{
    public static Item goldenEgg = new ItemGoldenEgg();

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(goldenEgg);
    }

    private static void register(Item item, String name)
    {
        GameRegistry.registerItem(item.setRegistryName(name));
    }

    @SideOnly(Side.CLIENT)
    private static void registerRender(Item item)
    {
        ModelResourceLocation model = new ModelResourceLocation(item.getRegistryName(), "inventory");
        ModelLoader.setCustomModelResourceLocation(item, 0, model);
        /*setCustomModelResourceLocation
        第一个参数是要被注册的物品。
第二个参数是这个物品的Metadata。Metadata是一个用于区分同一个物品或方块的不同状态的数据，比如钟表的十六种状态、羊毛的十六种颜色，在3.2.1节会讲到Metadata，默认为零就好了。
第三个参数就是这个物品模型的资源位置了，资源位置是类ModelResourceLocation的一个实例，它用于描述一个模型，在后面我们还会比较常用到这个类的。
        */
    }
}
```

`ModelResourceLocation`被用于标注模型的位置，通常为由冒号（`:`）和井号（`#`）分隔的三个字符串组成，对于我们这里构造的`ModelResourceLocation`，它的一部分通过调用物品的`getRegistryName`方法得到，第二部分由我们指定，为`inventory`，是一个固定的字符串，代表作为一个物品的渲染模型。

在这里，第一部分为`fmltutor:golden_egg`，第二部分为`inventory`，组合后的`ModelResourceLocation`就是`fmltutor:golden_egg#inventory`。Minecraft便会去相应的目录下寻找相应的资源：

- `fmltutor`指示游戏应该在`assets.fmltutor`包下找到这个资源
- `inventory`指示游戏应该在`assets.fmltutor.models.item`包下找到这个资源
- `golden_egg`指示这个资源就是`assets.fmltutor.models.item.golden_egg.json`，对应到源代码，就是`src/main/resources/assets/fmltutor/models/item/golden_egg.json`这一文件

`@SideOnly`注解的作用是注解这一方法、类等只作用于客户端或服务端。很明显，对于模型和材质的操作只会在客户端执行（实际上如果在服务端执行会出错），所以我们同时要在**`ClientProxy`的`preInit`阶段**中初始化：

**`src/main/java/com/github/ustc_zzzz/fmltutor/client/ClientProxy.java:`**

```java
package com.github.ustc_zzzz.fmltutor.client;

import com.github.ustc_zzzz.fmltutor.common.CommonProxy;

import net.minecraftforge.fml.common.event.FMLInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPostInitializationEvent;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;

public class ClientProxy extends CommonProxy
{
    @Override
    public void preInit(FMLPreInitializationEvent event)
    {
        super.preInit(event);
        new ItemRenderLoader();
    }

    @Override
    public void init(FMLInitializationEvent event)
    {
        super.init(event);
    }

    @Override
    public void postInit(FMLPostInitializationEvent event)
    {
        super.postInit(event);
    }
}
```

在`com.github.ustc_zzzz.fmltutor.client`下新建`ItemRenderLoader`类：

**`src/main/java/com/github/ustc_zzzz/fmltutor/client/ItemRenderLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.client;

import com.github.ustc_zzzz.fmltutor.item.ItemLoader;

public class ItemRenderLoader
{
    public ItemRenderLoader()
    {
        ItemLoader.registerRenders();
    }
}
```

现在在客户端，Forge会在`preInit`阶段，运行到`ItemRenderLoader`类的构造函数，进而运行到`ItemLoader`类中的`registerRenders`方法中的代码，也就是注册这个物品的渲染，而在服务端则不会运行。



#### 最后的模板

创建物品

在Item包中添加 ItemGoldEgg

```java
package ccreater.top.ccreater.testmod.item;

import net.minecraft.item.Item;

public class ItemGoldenEgg extends Item
{
    public ItemGoldenEgg()
    {
        super();
        this.setUnlocalizedName("goldenEgg");
    }
}
```



资源配置

```json
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/golden_egg"
    },
    "display": {
        "thirdperson": {
            "rotation": [ -90, 0, 0 ],
            "translation": [ 0, 1, -2 ],
            "scale": [ 0.55, 0.55, 0.55 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```



注册及资源



在item包中添加`ItemLoader`,配置注册函数和注册渲染函数

```java
public class ItemLoader
{
    public static Item goldenEgg = new ItemGoldenEgg();

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(goldenEgg);
    }

    private static void register(Item item, String name)
    {
        GameRegistry.registerItem(item.setRegistryName(name));
    }

    @SideOnly(Side.CLIENT)
    private static void registerRender(Item item)
    {
        ModelResourceLocation model = new ModelResourceLocation(item.getRegistryName(), "inventory");
        ModelLoader.setCustomModelResourceLocation(item, 0, model);
    }
```



在`Cilentproxy.preInit`处调用调用注册函数:`new ItemRenderLoader();`

`ItemRenderLoader`对应的具体实现为:

```java
public class ItemRenderLoader
{
    public ItemRenderLoader()
    {
        ItemLoader.registerRenders();
    }
}
```



### 制作方块

制作方块大体和制作物品相似,制作一个方块一共只比物品多一步：

1. 创建一个方块
2. 实例化并注册这个方块
3. 为这个方块对应的物品添加模型和材质
4. 为这个方块添加模型和材质

#### 创建一个方块

新建一个包`com.github.ustc_zzzz.fmltutor.block`，并新建文件`BlockGrassBlock.java`，在其中创建一个类，使其继承方块类：

**`src/main/java/com/github/ustc_zzzz/fmltutor/block/BlockGrassBlock.java:`**

```java
package com.github.ustc_zzzz.fmltutor.block;

import net.minecraft.block.Block;
import net.minecraft.block.material.Material;

public class BlockGrassBlock extends Block
{
    public BlockGrassBlock()
    {
        super(Material.ground);
        this.setUnlocalizedName("grassBlock");
        this.setHardness(0.5F);
        this.setStepSound(soundTypeGrass);
    }
}
```

一个方块初始化的时候和物品有一点不同，例如需要设定方块的材质，这里设定成和泥土一样的材质。

当然，就像上面那样，方块往往有很多需要设定的性质，现将一些常见的设定方法列举如下：

- `setBlockUnbreakable`方法用于设定方块的硬度为-1，即不能损坏。
- `setHardness`方法用于设定方块的硬度，如黑曜石是50，铁块5，金块3，圆石2，石头1.5，南瓜1，泥土0.5，甘蔗0，基岩-1。
- `setHarvestLevel`方法用于设定方块的可挖掘等级，如钻石镐是3，铁2，石1，木金0。
- `setLightLevel`方法用于设定方块的光照，其周围的光照为设定值x15，如岩浆1.0，对应15，红石火把0.5，对应7.5。
- `setLightOpacity`方法用于设定方块的透光率，数值越大透光率越低，如树叶和蜘蛛网是1，水和冰3。
- `setResistance`方法用于设定方块的爆炸抗性，如木头的抗性为4，石头为10，黑曜石为2000，基岩为6000000。
- `setStepSound`方法用于设定走在方块上的响声。
- `setTickRandomly`方法用于设定方块是否会接受随机Tick（如农作物）。



#### 实例化并注册这个方块

在`CommonProxy`类中添加下面的代码：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void preInit(FMLPreInitializationEvent event)
    {
        new ItemLoader(event);
        new BlockLoader(event);
    }
```

新建一个类`BlockLoader`，以完成对应的方块的注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/block/BlockLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.block;

import net.minecraft.block.Block;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;
import net.minecraftforge.fml.common.registry.GameRegistry;

public class BlockLoader
{
    public static Block grassBlock = new BlockGrassBlock();

    public BlockLoader(FMLPreInitializationEvent event)
    {
        register(grassBlock, "grass_block");
    }

    private static void register(Block block, String name)
    {
        GameRegistry.registerBlock(block.setRegistryName(name));
    }
}
```



#### 为这个方块对应的物品添加模型和材质

和物品一样，我们现在扩充一下`BlockLoader`类的代码：

**`src/main/java/com/github/ustc_zzzz/fmltutor/block/BlockLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.block;

import net.minecraft.block.Block;
import net.minecraft.client.resources.model.ModelResourceLocation;
import net.minecraft.item.Item;
import net.minecraftforge.client.model.ModelLoader;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;
import net.minecraftforge.fml.common.registry.GameRegistry;
import net.minecraftforge.fml.relauncher.Side;
import net.minecraftforge.fml.relauncher.SideOnly;

public class BlockLoader
{
    public static Block grassBlock = new BlockGrassBlock();

    public BlockLoader(FMLPreInitializationEvent event)
    {
        register(grassBlock, "grass_block");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(grassBlock);
    }

    private static void register(Block block, String name)
    {
        GameRegistry.registerBlock(block.setRegistryName(name));
    }

    @SideOnly(Side.CLIENT)
    private static void registerRender(Block block)
    {
        ModelResourceLocation model = new ModelResourceLocation(block.getRegistryName(), "inventory");
        ModelLoader.setCustomModelResourceLocation(Item.getItemFromBlock(block), 0, model);
    }
}
```

由于注册的是方块对应的物品的模型和材质，所以就如上面的代码描述的一样，和物品唯一不一样的地方就是，我们通过`Item`类的静态方法`getItemFromBlock`获取方块对应的物品，其他的和物品相同。

接下来的事情也十分顺理成章，只不过这里有一些微小的变动。

我们这次先新建一个文件夹：`src/main/resources/assets/fmltutor/models/block`，并在其中新建一个文件：`grass_block.json`：

**`src/main/resources/assets/fmltutor/models/block/grass_block.json:`**

```json
{
    "parent": "block/cube_all",
    "textures": {
        "all": "fmltutor:blocks/grass_block"
    }
}
```

在`src/main/resources/assets/fmltutor/models/item`里新建文件：`grass_block.json`：

**`src/main/resources/assets/fmltutor/models/item/grass_block.json:`**

```json
{
    "parent": "fmltutor:block/grass_block",
    "display": {
        "thirdperson": {
            "rotation": [ 10, -45, 170 ],
            "translation": [ 0, 1.5, -2.75 ],
            "scale": [ 0.375, 0.375, 0.375 ]
        }
    }
}
```



然后我们新建文件夹`src/main/resources/assets/fmltutor/textures/blocks`，在其中创建尺寸同样为16x16的图片文件`grass_block.png`（其实也仅仅是干草堆调了个色=_=||）：

**`src/main/resources/assets/fmltutor/textures/blocks/grass_block.png:`**

对`ItemRenderLoader`的修改：

**`src/main/java/com/github/ustc_zzzz/fmltutor/client/ItemRenderLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.client;

import com.github.ustc_zzzz.fmltutor.item.ItemLoader;

public class ItemRenderLoader
{
    public ItemRenderLoader()
    {
        ItemLoader.registerRenders();
        BlockLoader.registerRenders();
    }
}
```



#### 为这个方块添加模型和材质

 但是，当方块被放到地上的时候，我们会发现，方块并没有显示出应有的样子，而只是一个两种颜色交替的方块。这是因为刚刚我们仅仅注册了方块对应物品的模型和材质，而没有注册方块本身的模型和材质。 

Minecraft会将方块的状态和模型之间的关系信息放在`assets.minecraft.blockstates`文件夹下，同样，Minecraft会自动寻找对应的存放方块状态的文件夹，比如这里就是`assets.fmltutor.blockstates`，也就是`src/main/resources/assets/fmltutor/blockstates`文件夹，如果没有特殊设置，再在这个文件夹下寻找文件名和`<方块id>.json`相同的文件。

我们新建这样一个文件夹，并在其中新建一个文件`grass_block.json`：

**`src/main/resources/assets/fmltutor/blockstates/grass_block.json:`**

```json
{
    "variants": {
        "normal": { "model": "fmltutor:grass_block" }
    }
}
```

这个文件告诉游戏，这个方块使用`assets.fmltutor.models.block`包下的一个名为`grass_block.json`的文件作为模型，这也是物品模型被拆分成两个文件的原因。





### 将物品和方块放入创造模式物品栏

 其实这很简单，只要在物品和方块初始化的时候加上一句就好了 



**`src/main/java/com/github/ustc_zzzz/fmltutor/block/BlockGrassBlock.java（BlockGrassBlock()）:`**

```java
        this.setCreativeTab(CreativeTabs.tabBlock);
```

把这个方块放到了名为“方块”的创造模式物品栏里。

### 新建一个创造模式物品栏

Minecraft的所有物品栏都是`CreativeTabs`类的子类，我们首先新建包`com.github.ustc_zzzz.fmltutor.creativetab`，并在其下新建类`CreativeTabsFMLTutor`，使其继承`CreativeTabs`类：

**`src/main/java/com/github/ustc_zzzz/fmltutor/creativetab/CreativeTabsFMLTutor.java:`**

```java
package com.github.ustc_zzzz.fmltutor.creativetab;

import com.github.ustc_zzzz.fmltutor.item.ItemLoader;

import net.minecraft.creativetab.CreativeTabs;
import net.minecraft.item.Item;

public class CreativeTabsFMLTutor extends CreativeTabs
{
    public CreativeTabsFMLTutor()
    {
        super("fmltutor");
    }

    @Override
    public Item getTabIconItem()
    {
        return ItemLoader.goldenEgg;
    }
}
```

`getTabIconItem`方法，返回的是创造模式物品栏上显示的物品。

新建包`com.github.ustc_zzzz.fmltutor.creativetab`并在其下新建类`CreativeTabsLoader`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/creativetab/CreativeTabsLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.creativetab;

import net.minecraft.creativetab.CreativeTabs;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;

public class CreativeTabsLoader
{
    public static CreativeTabs tabFMLTutor;

    public CreativeTabsLoader(FMLPreInitializationEvent event)
    {
        tabFMLTutor = new CreativeTabsFMLTutor();
    }
}
```

并将物品注册进去：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemGoldenEgg.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import com.github.ustc_zzzz.fmltutor.creativetab.CreativeTabsLoader;

import net.minecraft.item.Item;

public class ItemGoldenEgg extends Item
{
    public ItemGoldenEgg()
    {
        super();
        this.setUnlocalizedName("goldenEgg");
        this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
    }
}
```

最后在`CommonProxy`中的`preInit`阶段添加代码，记得创造模式物品栏的初始化一定要在物品和方块的初始化之前：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void preInit(FMLPreInitializationEvent event)
    {
        new CreativeTabsLoader(event);
        new ItemLoader(event);
        new BlockLoader(event);
    }
```

打开游戏，你是不是看到了物品被注册到了新的创造模式物品栏

### 制作合成表

新建包`com.github.ustc_zzzz.fmltutor.crafting`，并新建文件`CraftingLoader.java`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.crafting;

import net.minecraftforge.fml.common.registry.GameRegistry;

public class CraftingLoader
{
    public CraftingLoader()
    {
        registerRecipe();
        registerSmelting();
        registerFuel();
    }

    private static void registerRecipe()
    {

    }

    private static void registerSmelting()
    {

    }

    private static void registerFuel()
    {

    }
}
```

向`registerRecipe`方法添加内容：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```java
    private static void registerRecipe()
    {
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.goldenEgg), new Object[]
        {
                "###", "#*#", "###", '#', Items.gold_ingot, '*', Items.egg
        });
        GameRegistry.addShapedRecipe(new ItemStack(BlockLoader.grassBlock), new Object[]
        {
                "##", "##", '#', Blocks.vine
        });
        GameRegistry.addShapelessRecipe(new ItemStack(Blocks.vine, 4), BlockLoader.grassBlock);
    }
```

前两句通过调用`addShapedRecipe`方法添加了有序合成表（如合成木棍等等）。

后一句通过调用`addShapelessRecipe`方法添加了无序合成表（如合成书等等）。

 Minecraft原版所有的方块和物品都被存放在`Blocks`类和`Items`类里。 

最后，在CommonProxy中的`init`阶段注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void init(FMLInitializationEvent event)
    {
        new CraftingLoader();
    }
```

### 制作烧炼规则

向`registerSmelting`方法添加内容：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```java
    private static void registerSmelting()
    {
        GameRegistry.addSmelting(BlockLoader.grassBlock, new ItemStack(Items.coal), 0.5F);
    }
```

第一个参数是待烧炼的物品，第二个参数是烧炼后的物品，第三个参数是烧炼后玩家可以得到的经验。

最后，在CommonProxy中的`init`阶段注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void init(FMLInitializationEvent event)
    {
        new CraftingLoader();
    }
```



### 制作燃料

向`registerFuel`方法添加内容：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```java
    private static void registerFuel()
    {
        GameRegistry.registerFuelHandler(new IFuelHandler()
        {
            @Override
            public int getBurnTime(ItemStack fuel)
            {
                return Items.diamond != fuel.getItem() ? 0 : 12800;
            }
        });
    }
```

注册燃料需要实现`IFuelHandler`接口，这里使用了匿名类以节省代码量。

实现`IFuelHandler`接口需要实现`getBurnTime`方法，该方法判断物品的烧炼时间，如果返回0，则为不能判断物品的烧炼时间。

这里的烧炼时间为gametick，**一秒为20个gametick**，下面列出一些常见的烧炼时间数据：

- 树苗　　100
- 木板　　200
- 煤炭　　1600
- 烈焰棒　2400
- 煤炭块　16000
- 岩浆桶　20000

这段代码添加了钻石作为燃料，想必读者也很容易看出这段代码的含义。

最后，在CommonProxy中的`init`阶段注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void init(FMLInitializationEvent event)
    {
        new CraftingLoader();
    }
```

### 本地化和国际化

> 本地化则是指当移植软件时，加上与特定区域设置有关的信息和翻译文件的过程。国际化是指在设计软件，将软件与特定语言及地区脱钩的过程。当软件被移植到不同的语言及地区时，软件本身不用做内部工程上的改变或修正。

本地化的国际化的英文分别是Localization和internationalization，由于字母过多，它们被简化成了L10n和i18n，其中i18n的简写由于其中间有足足十八个字母等原因而更加常用。

Minecraft本身就提供了本地化和国际化方案。在`assets/minecraft/lang`文件夹下，便有着各种语言的语言文件。

新建文件夹`src/main/resources/assets/fmltutor/lang`，并在其中新建文件`en_US.lang`，注意等号的两边没有空格：

**`src/main/resources/assets/fmltutor/lang/en_US.lang:`**

```
item.goldenEgg.name=Golden Egg

tile.grassBlock.name=Grass Block

itemGroup.fmltutor=FML Tutor
```

- `item.goldenEgg.name`便是金蛋的名称，这个名称由该物品的`setUnlocalizedName`方法设置
- `tile.grassBlock.name`便是草块的名称，这个名称由该方块的`setUnlocalizedName`方法设置
- `itemGroup.tabFMLTutor`便是新创造模式物品栏的名称

如果搞不清楚语言文件等号前面应该使用什么，可以先不写，在游戏中看一看，然后把不正常的部分写入语言文件。

当然，作为面向中国人制作的Mod，中文语言文件还是要有的。在同一个文件夹下新建文件`zh_CN.lang`：

**`src/main/resources/assets/fmltutor/lang/zh_CN.lang:`**

```
item.goldenEgg.name=金蛋

tile.grassBlock.name=草块

itemGroup.fmltutor=FML教程
```

 语言文件**一定要使用UTF-8编码**。 

 语言文件的文件名通常按照“[语言](https://zh.wikipedia.org/wiki/ISO_639-1)\\[国家](https://zh.wikipedia.org/wiki/ISO_3166-1)”代码标准。 



### 配置管理

首先，我们创建一个配置文件管理类，在包`com.github.ustc_zzzz.fmltutor.common`下创建文件`ConfigLoader.java`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/ConfigLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.common;

import net.minecraftforge.common.config.Configuration;
import net.minecraftforge.fml.common.event.FMLPreInitializationEvent;
import org.apache.logging.log4j.Logger;

public class ConfigLoader
{
    private static Configuration config;

    private static Logger logger;

    public static int diamondBurnTime;

    public ConfigLoader(FMLPreInitializationEvent event)
    {
        logger = event.getModLog();
        config = new Configuration(event.getSuggestedConfigurationFile());//这里的event.getSuggestedConfigurationFile()，就是Forge推荐的配置文件位置。这个位置在游戏根目录的config文件夹下，名为“<Mod id>.cfg”，这里就是“fmltutor.cfg”。

        config.load();//读入配置
        load();
    }

    public static void load()
    {
        logger.info("Started loading config. ");
        String comment;

        comment = "How many seconds can a diamond burn in a furnace. ";
        diamondBurnTime = config.get(Configuration.CATEGORY_GENERAL, "diamondBurnTime", 640, comment).getInt();
//加载配置
        config.save();//保存配置
        logger.info("Finished loading config. ");
    }

    public static Logger logger()
    {
        return logger;
    }
}
```

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/ConfigLoader.java（部分）:`**

```java
        comment = "How many seconds can a diamond burn in a furnace. ";
        diamondBurnTime = config.get(Configuration.CATEGORY_GENERAL, "diamondBurnTime", 640, comment).getInt();
```

在一个正常的Forge Mod配置文件里，会有多个类别，Forge提供了一种类别“`general`”（`Configuration.CATEGORY_GENERAL`），`get`方法的第一个参数就是表示“`general`”类别。

`get`方法的第三个参数，是该键的默认值（这里是640），当对应的键不存在时，就会返回该默认值。

`get`方法的第四个参数，是该键的注释，用于描述该项配置的。

那么很明显，`get`方法的作用，就是获取`diamondBurnTime`键对应的值。

在`CommonProxy`中注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void preInit(FMLPreInitializationEvent event)
    {
        new ConfigLoader(event);
        new CreativeTabsLoader(event);
        new ItemLoader(event);
        new BlockLoader(event);
    }
```

最后再修改 CraftingLoader 





### 自己实践遇到的问题

1. eclipse运行时不会把图片文件一起移动到bin文件夹下,得自己手动移动(解决方法:刷新以下资源文件夹)
2. 



## 初级部分

### 注册已有的事件

Forge的事件系统一直在Forge中占有十分重要的地位，可以这么说，没有事件，就没有Mod。大家可以注意到，主类的`preInit`，`init`，`postInit`方法，全部都是事件驱动的。换句话说，理论上一个Mod的开发教程本身应该从事件讲起。

Forge的事件系统几乎涵盖了方方面面，从服务端到客户端，从世界生成到物品方块行为，从玩家行为到一般实体行为，等等。

Forge的事件系统分为两类，一类是FML生命周期事件，一类是Minecraft事件。

#### FML生命周期事件

FML生命周期事件，顾名思义，就是FML加载、关闭、和Mod加载等等相关的事件，这些希望监听对应事件的方法使用`@EventHandler`注解修饰，并且应在被`@Mod`注解修饰的主类下，Forge会寻找并注册仅含一个参数并且参数符合特定类型的方法。如下面三个FML生命周期事件是最常用的：

- `FMLPreInitializationEvent`
- `FMLInitializationEvent`
- `FMLPostInitializationEvent`

这三个事件的使用方法已经讲过，此处不再赘述。

还有下面两个事件：

- `FMLConstructionEvent`在Mod开始加载时触发。
- `FMLLoadCompleteEvent`在Mod加载完成时触发。

除上面这些之外，还有下面的这些比较常用的用于服务端的FML生命周期事件：

- `FMLServerAboutToStartEvent`
- `FMLServerStartingEvent`
- `FMLServerStartedEvent`
- `FMLServerStoppingEvent`
- `FMLServerStoppedEvent`

#### Minecraft事件

开发者只需要注册一个包含监听这些事件的方法的类，Forge就会挂钩上这些方法。这些方法使用`@SubscribeEvent`注解进行修饰，Forge寻找并挂钩这些方法的方式和上面的FML生命周期事件类似，只不过由于挂钩的方式不同，调用的时候效率要更高。

首先我们创造一个类。在包`com.github.ustc_zzzz.fmltutor.common`下新建一个文件`EventLoader.java`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java:`**

```java
package com.github.ustc_zzzz.fmltutor.common;

import net.minecraftforge.common.MinecraftForge;
import net.minecraftforge.event.entity.player.PlayerInteractEvent;
import net.minecraftforge.fml.common.eventhandler.SubscribeEvent;
import net.minecraftforge.fml.common.gameevent.PlayerEvent;

public class EventLoader
{
    public EventLoader()
    {
        MinecraftForge.EVENT_BUS.register(this);
    }

    @SubscribeEvent
    public void onPlayerItemPickup(PlayerEvent.ItemPickupEvent event)
    {
        if (event.player.isServerWorld())//检测时候时服务端
        {
            String info = String.format("%s picks up: %s", event.player.getName(), event.pickedUp.getEntityItem());
            ConfigLoader.logger().info(info);
        }
    }

    @SubscribeEvent
    public void onPlayerInteract(PlayerInteractEvent event)
    {
        if (!event.world.isRemote)
        {
            String info = String.format("%s interacts with: %s", event.entityPlayer.getName(), event.pos);
            ConfigLoader.logger().info(info);
        }
    }
}
```

 `@SubscribeEvent`注解的作用是Forge在你注册这个类的时候，会扫描所有具有该注解的方法，然后挂钩。 Forge会根据方法的参数类型来区分不同的事件。比如，这里的`onPlayerItemPickup`方法挂钩的就是物品即将被捡起的时候触发的事件`PlayerEvent.ItemPickupEvent`，而`onPlayerInteract`方法挂钩的就是玩家在和物品或方块互动的时候触发的事件`PlayerInteractEvent`。

`@SubscribeEvent`注解有两个参数，其中一个是`receiveCanceled`，与是否取消该事件相关，默认为`false`，这个参数不太常用，我们不去管它。还有一个参数是`priority`，比较常用，表示事件的优先级，可能的情况有五种：

- `EventPriority.HIGHEST`
- `EventPriority.HIGH`
- `EventPriority.NORMAL`
- `EventPriority.LOW`
- `EventPriority.LOWEST`

默认的优先级是`EventPriority.NORMAL`，当然，如果想自定优先级，往往都会选择`EventPriority.HIGH`，和`EventPriority.HIGHEST`

我们使用`EventBus`的`register`方法，注册了所有我们想要注册的事件。

除此之外，Forge还提供了需要在`MinecraftForge.TERRAIN_GEN_BUS`上注册的地形生成事件，需要在`MinecraftForge.ORE_GEN_BUS`上注册的矿物生成事件等等。

最后在`CommonProxy`中注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```
    public void init(FMLInitializationEvent event)
    {
        new CraftingLoader();
        new EventLoader();
    }
```

 ####  所有Minecraft事件 

[https://github.com/ustc-zzzz/fmltutor/blob/book/%E9%99%84%E5%BD%95A-%E4%BA%8B%E4%BB%B6%E5%88%97%E8%A1%A8.md](https://github.com/ustc-zzzz/fmltutor/blob/book/附录A-事件列表.md) 

### 自定义新的事件类

我们在`EventLoader`类中新建一个我们想要的事件类。

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```
    @Cancelable
    public static class PlayerRightClickGrassBlockEvent extends net.minecraftforge.event.entity.player.PlayerEvent
    {
        public final BlockPos pos;
        public final World world;

        public PlayerRightClickGrassBlockEvent(EntityPlayer player, BlockPos pos, World world)
        {
            super(player);
            this.pos = pos;
            this.world = world;
        }
    }
```

很明显，这个类和玩家右键草块相关。该事件类继承了`PlayerEvent`，`@Cancelable`注解表明了该事件可取消。

#### 自定义事件的注册机制

在上一部分，我们注意到`FMLCommonHandler.instance().bus()`和`MinecraftForge.EVENT_BUS`均为`EventBus`类型。该类型提供了名为`register`的方法使得事件可以被注册。

显然，我们自己也可以创建这样一个`EventBus`，并且使得所有自定义的事件在这里被注册。

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```
    public static final EventBus EVENT_BUS = new EventBus();
```



#### 使自定义事件会被触发到

`EventBus`本身提供了一个名为`post`的方法，负责执行事件。大家如果经常翻源代码的话，会在Minecraft的许多类中找到这个方法的调用。这里我们希望在玩家点击草块时触发，我们也如法炮制。

**`src/main/java/com/github/ustc_zzzz/fmltutor/block/BlockGrassBlock.java（部分）:`**

```java
    @Override
    public boolean onBlockActivated(World worldIn, BlockPos pos, IBlockState state, EntityPlayer playerIn,
            EnumFacing side, float hitX, float hitY, float hitZ)
    {
        EventLoader.PlayerRightClickGrassBlockEvent event;
        event = new EventLoader.PlayerRightClickGrassBlockEvent(playerIn, pos, worldIn);
        EventLoader.EVENT_BUS.post(event);
        if (!event.isCanceled() && !worldIn.isRemote)
        {
            worldIn.setBlockToAir(pos);
            return true;
        }
        return false;
    }
```

很明显，这段代码的意思是，如果事件被取消了，就阻止草块变成空气，否则草块就会变成空气。

#### 自定义事件的实现

自定义事件的实现和Forge提供的完全一样，只不过我们要找准在哪里注册。

在`EventLoader`类中添加一个方法。

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```
    @SubscribeEvent
    public void onPlayerClickGrassBlock(PlayerRightClickGrassBlockEvent event)
    {
        if (!event.world.isRemote)
        {
            BlockPos pos = event.pos;
            Entity tnt = new EntityTNTPrimed(event.world, pos.getX() + 0.5, pos.getY() + 0.5, pos.getZ() + 0.5, null);
            event.world.spawnEntityInWorld(tnt);
        }
    }
```

很明显，这里定义了当玩家两手空空时的行为。

然后我们注册这个事件。

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```java
    public EventLoader()
    {
        MinecraftForge.EVENT_BUS.register(this);
        EventLoader.EVENT_BUS.register(this);
    }
```



### 制作工具

 以制作一个红石镐为例 

#### 创建一个物品

在包`com.github.ustc_zzzz.fmltutor.item`下新建一个文件`ItemRedstonePickaxe.java`，并让`ItemRedstonePickaxe`类继承`ItemPickaxe`类：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstonePickaxe.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import com.github.ustc_zzzz.fmltutor.creativetab.CreativeTabsLoader;

import net.minecraft.item.Item;
import net.minecraft.item.ItemPickaxe;
import net.minecraftforge.common.util.EnumHelper;

public class ItemRedstonePickaxe extends ItemPickaxe
{
    public static final Item.ToolMaterial REDSTONE = EnumHelper.addToolMaterial("REDSTONE", 3, 16, 16.0F, 0.0F, 10);

    public ItemRedstonePickaxe()
    {
        super(REDSTONE);
        this.setUnlocalizedName("redstonePickaxe");
        this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
    }
}
```

我们先来说说这一行：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstonePickaxe.java（部分）:`**

```java
    public static final Item.ToolMaterial REDSTONE = EnumHelper.addToolMaterial("REDSTONE", 3, 16, 16.0F, 0.0F, 10);
```

这里添加了一个作为工具需要的枚举类，ToolMaterial的实例。顾名思义，ToolMaterial就是工具或武器使用的材料，Forge本身定义了五种材料：WOOD，STONE，IRON，EMERALD，GOLD。它们分别表示木头、石头、铁、钻石、金。

我们来看看ToolMaterial的构造方法：

```java
private ToolMaterial(int harvestLevel, int maxUses, float efficiency, float damageVsEntity, int enchantability) {...}
```

和五种材料的参数：

- `WOOD(0, 59, 2.0F, 0.0F, 15),`
- `STONE(1, 131, 4.0F, 1.0F, 5),`
- `IRON(2, 250, 6.0F, 2.0F, 14),`
- `EMERALD(3, 1561, 8.0F, 3.0F, 10),`
- `GOLD(0, 32, 12.0F, 0.0F, 22);`

ToolMaterial的构造方法共有五个参数：

- `harvestLevel`参数表示制作出的工具等级。这一点在镐中尤其明显，如木头为0，只能挖掘对应等级为0的方块才能掉落物品，如石头等，而钻石为3，就可以挖掘出对应等级为3的，其他镐挖不出物品的方块，如黑曜石。这里使用了最高等级3
- `maxUses`参数表示制作出的工具对应耐久。如钻石工具就是1561，耐久最高，而木工具为59，耐久最低。这里刻意降低了该数值，为16
- `efficiency`参数表示制作出的工具使用效率。使用效率和该参数的值成正比。这里刻意提高了该数值，为16.0F
- `damageVsEntity`参数表示攻击伤害力度。同样该力度和该参数的值成正相关。这里为0.0F，表示攻击力很低
- `enchantability`参数与附魔等级相关。Minecraft中关于附魔等级的系统十分复杂。但是有一点，就是该值越高，对应的附魔就越容易得到高等级。这也是为何金更容易得到高等级附魔，而石头得到的附魔就相当低。这里为10，和钻石相同

`EnumHelper`的作用就是为Minecraft的一些枚举类型注册新的实例，该方法的第一个参数为实例的名称，后面的参数就是该枚举类型构造方法需要的参数。比如这里就是向ToolMaterial枚举类型添加一个名为`REDSTONE`的实例，并提供相应的参数。

#### 为这个物品添加物品模型和材质

该物品的模型：

**`src/main/resources/assets/fmltutor/models/item/redstone_pickaxe.json:`**

```
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_pickaxe"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

和对应的材质

![redstone_pickaxe](https://fmltutor.ustc-zzzz.net/resources/redstone_pickaxe.png)

#### 注册渲染,物品及配置

语言文件和在`GameRegistry`中注册（这里需要稍微注意一下的可能是`ToolMaterial`和物品的先后注册顺序）：

**`src/main/resources/assets/fmltutor/lang/en_US.lang（部分）:`**

```
item.redstonePickaxe.name=Redstone Pickaxe
```

**`src/main/resources/assets/fmltutor/lang/zh_CN.lang（部分）:`**

```
item.redstonePickaxe.name=红石镐
```

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java（部分）:`**

```
    public static Item goldenEgg = new ItemGoldenEgg();
    public static ItemPickaxe redstonePickaxe = new ItemRedstonePickaxe();

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
        register(redstonePickaxe, "redstone_pickaxe");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(goldenEgg);
        registerRender(redstonePickaxe);
    }
```

当然最后，我们也可以加上合成表：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```java
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstonePickaxe), new Object[]
        {
                "###", " * ", " * ", '#', Items.redstone, '*', Items.stick
        });
```

### 制作食物

 以制作一个红石苹果为例 

#### 制作一个崭新的食物

在包`com.github.ustc_zzzz.fmltutor.item`下新建一个文件`ItemRedstoneApple.java`，并让`ItemRedstoneApple`类继承`ItemFood`类：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstoneApple.java:`**

```
package com.github.ustc_zzzz.fmltutor.item;

import net.minecraft.item.ItemFood;

import com.github.ustc_zzzz.fmltutor.creativetab.CreativeTabsLoader;

public class ItemRedstoneApple extends ItemFood
{
    public ItemRedstoneApple()
    {
        super(4, 0.6F, false);
        this.setAlwaysEdible();
        this.setUnlocalizedName("redstoneApple");
        this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
    }
}
```

`ItemFood`类的构造方法一共有三个参数：

- 第一个参数`amount`表示该食物所能回复的饥饿值，这里被设定成和苹果相同，即`4`。
- 第二个参数`saturation`表示该食物所能添加的相对饱和度，其正比于饱和度和饥饿值的比值，这里设定为`0.6F`。
- 最后一个参数`isWolfFood`表示该食物能否被狼食用，这里简单地设置为`false`就可以了。

饱和度的计算：`2 * amount * saturation`。如面包的`amount`为5，其`saturation`为0.6F，对应的饱和度为2 *5* 0.6 = 6

为了方便读者，我们在这里列了一个常见食物对应的`amount`和`saturation`表。

| 食物     | amount | saturation |
| :------- | :----- | :--------- |
| 苹果     | 4      | 0.3F       |
| 面包     | 5      | 0.6F       |
| 生猪排   | 3      | 0.3F       |
| 熟猪排   | 8      | 0.8F       |
| 曲奇     | 2      | 0.1F       |
| 西瓜片   | 2      | 0.3F       |
| 生牛肉   | 3      | 0.3F       |
| 牛排     | 8      | 0.8F       |
| 生鸡肉   | 2      | 0.3F       |
| 熟鸡肉   | 6      | 0.6F       |
| 腐肉     | 4      | 0.1F       |
| 蜘蛛眼   | 2      | 0.8F       |
| 烤马铃薯 | 5      | 0.6F       |
| 毒马铃薯 | 2      | 0.3F       |
| 金萝卜   | 6      | 1.2F       |
| 南瓜派   | 8      | 0.3F       |

方法`setAlwaysEdible`表示该食物何时何地都可以被食用，即便玩家不需要回复饥饿度和饱和值。

下面就是一些例行公事了（模型、贴图、语言文件、以及注册）（贴图同为金苹果调色=_=||）：

**`src/main/resources/assets/fmltutor/models/item/redstone_apple.json:`**

```
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_apple"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

**`src/main/resources/assets/fmltutor/textures/items/redstone_apple.png:`**

![redstone_apple](https://fmltutor.ustc-zzzz.net/resources/redstone_apple.png)

**`src/main/resources/assets/fmltutor/lang/en_US.lang（部分）:`**

```
item.redstoneApple.name=Redstone Apple
```

**`src/main/resources/assets/fmltutor/lang/zh_CN.lang（部分）:`**

```
item.redstoneApple.name=红石苹果
```

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java（部分）:`**

```
    public static Item goldenEgg = new ItemGoldenEgg();
    public static ItemPickaxe redstonePickaxe = new ItemRedstonePickaxe();
    public static ItemFood redstoneApple = new ItemRedstoneApple();

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
        register(redstonePickaxe, "redstone_pickaxe");
        register(redstoneApple, "redstone_apple");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(goldenEgg);
        registerRender(redstonePickaxe);
        registerRender(redstoneApple);
    }
```

当然我们也可以加上合成表：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstoneApple), new Object[]
        {
                "###", "#*#", "###", '#', Items.redstone, '*', Items.apple
        });
```

打开游戏试试吧～

#### 为食物添加食用后的药水效果

实际上，`ItemFood`类本身就预置了药水效果的轮子，我们在构造函数中加上这么一句：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstoneApple.java（部分）:`**

```
        this.setPotionEffect(Potion.absorption.id, 10, 1, 1.0F);
```

`setPotionEffect`方法共有四个参数：

- 第一个参数表示对应药水效果的`potionId`，读者可以去`net.minecraft.potion.Potion`类中查看MC提供的二十四种药水效果，这里为伤害吸收。
- 第二个参数表示对应药水效果的持续时间，以秒计数，这里为十秒。
- 第三个参数表示对应药水效果的等级，很明显，0为一级，1为二级，2为三级，以此类推，这里为二级。
- 最后一个参数表示产生该药水效果的概率，这里为100%。

到这里我们就完成了对于添加食用食物后的药水效果的设置，这对大部分的食物设定来说，是够用了的。事实上，MC游戏本身的大部分食物，它们食用后的药水效果（如食用腐肉后产生的饥饿效果，食用蜘蛛眼后产生的中毒效果）都是这么设定的。

#### 为食物添加食用后的更多效果

当然，总有例外，例如食用河豚或金苹果后产生的多种药水效果，就不能通过上面的方法完成。

`ItemFood`类提供了一个方法`onFoodEaten`，我们可以把它覆写掉：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstoneApple.java（部分）:`**

```
    @Override
    public void onFoodEaten(ItemStack stack, World worldIn, EntityPlayer player)
    {
        if (!worldIn.isRemote)
        {
            player.addPotionEffect(new PotionEffect(Potion.saturation.id, 200, 1));
            player.addExperience(10);
        }
        super.onFoodEaten(stack, worldIn, player);
    }
```

这段代码的意思可能已经比较明显了：除了伤害吸收二，食用该食物还会给玩家带来十秒的饱和二效果，和十点经验。这里有一点不同的地方，就是`PotionEffect`的构造函数使用的时间是以gametick计数的。

### 制作盔甲

和ToolMaterial类似，ArmorMaterial表示的就是盔甲的材质。

我们在包`com.github.ustc_zzzz.fmltutor.item`下新建一个文件`ItemRedstoneArmor.java`，并让`ItemRedstoneArmor`类继承`ItemArmor`类：

#### 盔甲类

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemRedstoneArmor.java:`**

```java
package com.github.ustc_zzzz.fmltutor.item;

import com.github.ustc_zzzz.fmltutor.FMLTutor;

import net.minecraft.item.ItemArmor;
import net.minecraftforge.common.util.EnumHelper;

public class ItemRedstoneArmor extends ItemArmor
{
    public static final ItemArmor.ArmorMaterial REDSTONE_ARMOR = EnumHelper.addArmorMaterial("REDSTONE",
            FMLTutor.MODID + ":" + "redstone", 10, new int[]
            { 2, 6, 4, 2 }, 10);
    /*ArmorMaterial的构造方法(或addArmorMaterial)共有四个参数：

name   参数与该ArmorMaterial的材质所在位置有关，这一部分的稍后面会讲到。这里是“fmltutor:redstone”。
maxDamage    参数和该ArmorMaterial对应的盔甲的耐久成正比。这里刻意降低了大小，为10。
reductionAmounts     参数的四个元素表示对应盔甲的头盔、胸甲、护腿、和靴子抵御伤害的能力，如皮甲分别为1，3，2，1，和为7，钻石甲分别为3，8，6，3，和为20，请不要让四个元素值的和超过这个值(超过好像是无敌?)。这里为2，6，4，2，和为14。
enchantability     参数和ToolMaterial一样，和对应盔甲的附魔能力正相关，同样，金盔甲的附魔能力最高。这里为10。*/

    public ItemRedstoneArmor(int armorType)
    {
        super(REDSTONE_ARMOR, REDSTONE_ARMOR.ordinal(), armorType);
    }
}
```

#### 为盔甲的4个部分配置

在其对应的盔甲类中添加



```java
  public static class Helmet extends ItemRedstoneArmor
    {
        public Helmet()
        {
            super(0);
            this.setUnlocalizedName("redstoneHelmet");
            this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
        }
    }

    public static class Chestplate extends ItemRedstoneArmor
    {
        public Chestplate()
        {
            super(1);
            this.setUnlocalizedName("redstoneChestplate");
            this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
        }
    }

    public static class Leggings extends ItemRedstoneArmor
    {
        public Leggings()
        {
            super(2);
            this.setUnlocalizedName("redstoneLeggings");
            this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
        }
    }

    public static class Boots extends ItemRedstoneArmor
    {
        public Boots()
        {
            super(3);
            this.setUnlocalizedName("redstoneBoots");
            this.setCreativeTab(CreativeTabsLoader.tabFMLTutor);
        }
    }
```

**`src/main/resources/assets/fmltutor/lang/en_US.lang（部分）:`**

```json
item.redstoneHelmet.name=Redstone Helmet
item.redstoneChestplate.name=Redstone Chestplate
item.redstoneLeggings.name=Redstone Leggings
item.redstoneBoots.name=Redstone Boots
```

**`src/main/resources/assets/fmltutor/models/item/redstone_helmet.json:`**

```json
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_helmet"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

**`src/main/resources/assets/fmltutor/models/item/redstone_chestplate.json:`**

```json
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_chestplate"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

**`src/main/resources/assets/fmltutor/models/item/redstone_leggings.json:`**

```json
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_leggings"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

**`src/main/resources/assets/fmltutor/models/item/redstone_boots.json:`**

```java
{
    "parent": "builtin/generated",
    "textures": {
        "layer0": "fmltutor:items/redstone_boots"
    },
    "display": {
        "thirdperson": {
            "rotation": [ 0, 90, -35 ],
            "translation": [ 0, 1.25, -3.5 ],
            "scale": [ 0.85, 0.85, 0.85 ]
        },
        "firstperson": {
            "rotation": [ 0, -135, 25 ],
            "translation": [ 0, 4, 2 ],
            "scale": [ 1.7, 1.7, 1.7 ]
        }
    }
}
```

**`src/main/resources/assets/fmltutor/textures/items/redstone_helmet.png:`**

![redstone_helmet](https://fmltutor.ustc-zzzz.net/resources/redstone_helmet.png)

**`src/main/resources/assets/fmltutor/textures/items/redstone_chestplate.png:`**

![redstone_chestplate](https://fmltutor.ustc-zzzz.net/resources/redstone_chestplate.png)

**`src/main/resources/assets/fmltutor/textures/items/redstone_leggings.png:`**

![redstone_leggings](https://fmltutor.ustc-zzzz.net/resources/redstone_leggings.png)

**`src/main/resources/assets/fmltutor/textures/items/redstone_boots.png:`**

![redstone_boots](https://fmltutor.ustc-zzzz.net/resources/redstone_boots.png)

注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/item/ItemLoader.java（部分）:`**

```java
    public static ItemArmor redstoneHelmet = new ItemRedstoneArmor.Helmet();
    public static ItemArmor redstoneChestplate = new ItemRedstoneArmor.Chestplate();
    public static ItemArmor redstoneLeggings = new ItemRedstoneArmor.Leggings();
    public static ItemArmor redstoneBoots = new ItemRedstoneArmor.Boots();

    public ItemLoader(FMLPreInitializationEvent event)
    {
        register(goldenEgg, "golden_egg");
        register(redstonePickaxe, "redstone_pickaxe");
        register(redstoneApple, "redstone_apple");

        register(redstoneHelmet, "redstone_helmet");
        register(redstoneChestplate, "redstone_chestplate");
        register(redstoneLeggings, "redstone_leggings");
        register(redstoneBoots, "redstone_boots");
    }

    @SideOnly(Side.CLIENT)
    public static void registerRenders()
    {
        registerRender(goldenEgg);
        registerRender(redstonePickaxe);
        registerRender(redstoneApple);

        registerRender(redstoneHelmet);
        registerRender(redstoneChestplate);
        registerRender(redstoneLeggings);
        registerRender(redstoneBoots);
    }
```

加点合成表：

**`src/main/java/com/github/ustc_zzzz/fmltutor/crafting/CraftingLoader.java（部分）:`**

```java
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstoneHelmet), new Object[]
        {
                "###", "# #", '#', Items.redstone
        });
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstoneChestplate), new Object[]
        {
                "# #", "###", "###", '#', Items.redstone
        });
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstoneLeggings), new Object[]
        {
                "###", "# #", "# #", '#', Items.redstone
        });
        GameRegistry.addShapedRecipe(new ItemStack(ItemLoader.redstoneBoots), new Object[]
        {
                "# #", "# #", '#', Items.redstone
        });
```

现在打开游戏，应该就可以看到全套盔甲了。

#### 添加穿上盔甲后人物效果

虽然我们指定了盔甲对应物品的材质，我们还没有指定盔甲本身的材质。

盔甲的材质图是两个大小为64x32的图片。还记得刚刚说的ArmorMaterial的构造方法的`name`参数吗？那个就决定了这两个图片的位置。

例如，钻石的`name`参数为`diamond`，其两张图片的位置就是`textures/models/armor/diamond_layer_1.png`和`textures/models/armor/diamond_layer_2.png`。

这里我们的ArmorMaterial的`name`参数为`fmltutor:redstone`，其两张图片的位置就是`fmltutor:textures/models/armor/redstone_layer_1.png`和`fmltutor:textures/models/armor/redstone_layer_2.png`了。我们在那里新建文件夹，把我们想要的两张图放进去就可以了。

现在打开原版的材质图，我们可以注意到一团乱糟糟的外观碎片被放到了一起。实际上，这些碎片的摆放位置都是有规律的：

![armor_texture_analysis](https://fmltutor.ustc-zzzz.net/resources/armor_texture_analysis.png)

（材质分区图，其中F表示前面，B表示后面，L表示左面。R表示右面，U表示顶面，D表示底面，紫色背景表示尺寸，每格大小为7x7，边框尺寸为1）

我们注意到，这一张材质图被分成了五个大部分，每一个部分都有不同的尺寸。它们分别为头（Head，8x8x8），头饰（Headwear，8x8x8），下肢（RightLeg/LeftLeg，4x12x4），身体（Body，8x12x4），和上肢（RightArm/LeftArm，4x12x4）。每一个部分分成了六个小部分，表示六个面。

那。。。为什么是两张图呢？

这是因为当游戏渲染不同的盔甲的时候，使用的材质图不一样。当游戏渲染护腿时使用第二张图，这里就是`redstone_layer_2.png`，渲染其他类型的盔甲时使用第一张图，这里为`redstone_layer_1.png`。

游戏会根据玩家已经穿戴的盔甲，决定哪一部分被渲染：

- 当玩家穿戴上头盔，游戏渲染第一张图的Head和Headwear部分。
- 当玩家穿戴上胸甲，游戏渲染第一张图的Body和RightArm/LeftArm部分。
- 当玩家穿戴上护腿，游戏渲染第二张图的Body和RightLeg/LeftLeg部分。
- 当玩家穿戴上靴子，游戏渲染第一张图的RightLeg/LeftLeg部分。

这里准备了一张已经划分好不同部分的，大小为64x32的图，以方便读者设计盔甲。读者可以下载然后修改：

![armor_texture](https://fmltutor.ustc-zzzz.net/resources/armor_texture.png)

我们这里使用这样的两张图（没错。。。调色。。。）：

**`src/main/resources/assets/fmltutor/textures/models/armor/redstone_layer_1.png:`**

![redstone_layer_1](https://fmltutor.ustc-zzzz.net/resources/redstone_layer_1.png)

**`src/main/resources/assets/fmltutor/textures/models/armor/redstone_layer_2.png:`**

![redstone_layer_2](https://fmltutor.ustc-zzzz.net/resources/redstone_layer_2.png)

打开游戏试试吧～

### 制作伤害类型

#### 常用的DamageSource



原版提供了一个`DamageSource`类，并且预置了一些常用的`DamageSource`：

- `public static DamageSource inFire;`
  当站在火中时产生
- `public static DamageSource lightningBolt;`
  当遭雷劈时产生
- `public static DamageSource onFire;`
  当着火时产生
- `public static DamageSource lava;`
  当在岩浆中产生
- `public static DamageSource inWall;`
  当被方块窒息时产生
- `public static DamageSource drown;`
  当被水窒息时产生
- `public static DamageSource starve;`
  当饥饿值为零时产生
- `public static DamageSource cactus;`
  当被仙人掌刺伤时产生
- `public static DamageSource fall;`
  当受到跌落伤害时产生
- `public static DamageSource outOfWorld;`
  当跌落出这个世界时产生
- `public static DamageSource generic;`
  当死亡原因未知时产生
- `public static DamageSource magic;`
  当受到有伤害效果药水伤害时产生
- `public static DamageSource wither;`
  当被凋灵效果伤害时产生
- `public static DamageSource anvil;`
  当头顶铁砧时产生
- `public static DamageSource fallingBlock;`
  当头顶掉落的方块时产生

当希望对实体产生对应伤害时，就可以通过调用实体的`attackEntityFrom`方法，比如下面的例子：

```java
player.attackEntityFrom(DamageSource.lightningBolt, 8.0F);
```

意思就是对装*过度（误）的玩家产生八滴血的雷劈伤害。

#### 创造一个新的DamageSource

我们注意到，`DamageSource`类只有一个构造方法参数：

```
public DamageSource(String damageTypeIn)
```

这个参数就是`DamageSource`的类型，决定着玩家死亡后会输出什么样的信息。

我们打开Minecraft原版的zh_CN.lang文件：

```
...
death.attack.indirectMagic.item=%1$s 被 %2$s 用 %3$s 杀死了
death.attack.lava=%1$s 试图在岩浆里游泳
death.attack.lava.player=%1$s 在逃离 %2$s 时试图在岩浆里游泳
death.attack.lightningBolt=%1$s 被闪电击中
death.attack.magic=%1$s 被魔法杀死了
death.attack.mob=%1$s 被 %2$s 杀死了
death.attack.onFire=%1$s 被烧死了
death.attack.onFire.player=%1$s 在试图与 %2$s 战斗时被烤的酥脆
death.attack.outOfWorld=%1$s 掉出了这个世界
death.attack.player=%1$s 被 %2$s 杀死了
death.attack.player.item=%1$s 被 %2$s 用 %3$s 杀死了
death.attack.starve=%1$s 饿死了
death.attack.thorns=%1$s 在试图伤害 %2$s 时被杀
death.attack.thrown=%1$s 被 %2$s 给砸死了
death.attack.thrown.item=%1$s 被 %2$s 用 %3$s 给砸死了
death.attack.wither=%1$s 凋零了
...
```

换言之，玩家死亡收到的信息，就是`death.attack.`，或者`death.attack..item`。

我们新建这样一个事件：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```java
    @SubscribeEvent
    public void onEntityInteract(EntityInteractEvent event)
    {
        EntityPlayer player = event.entityPlayer;
        if (player.isServerWorld() && event.target instanceof EntityPig)
        {
            EntityPig pig = (EntityPig) event.target;
            ItemStack stack = player.getCurrentEquippedItem();
            if (stack != null && (stack.getItem() == Items.wheat || stack.getItem() == Items.wheat_seeds))
            {
                player.attackEntityFrom((new DamageSource("byPig")).setDifficultyScaled().setExplosion(), 8.0F);
                player.worldObj.createExplosion(pig, pig.posX, pig.posY, pig.posZ, 2.0F, false);
                pig.setDead();
            }
        }
    }
```

并在语言文件中加上：

**`src/main/resources/assets/fmltutor/lang/en_US.lang（部分）:`**

```
death.attack.byPig=%s was dead because of a pig! 
```

**`src/main/resources/assets/fmltutor/lang/zh_CN.lang（部分）:`**

```
death.attack.byPig=%s被猪弄死了！ 
```

读者应该能够看明白，这段代码的作用就是当玩家向猪试图喂食小麦或者小麦种子的时候，因为喂错饲料而发怒（误）的那头猪会Boom，并给玩家一定的伤害。

#### DamageSource的属性

刚刚读者可能已经注意到了，我们为这个`DamegeSource`赋予了两个属性：

- `setDefficultyScaled`方法设置的属性表示受到的伤害随着难度的变化而变化。
- `setExplosion`方法设置的属性表示该伤害由爆炸造成，爆炸保护附魔会起到作用。

除此之外，还可以设置`DamageSource`的其他属性：

- `setDamageBypassesArmor`设置伤害不会因为盔甲的保护而折减。
- `setDamageAllowedInCreativeMode`设置创造模式同样会受到伤害。
- `setDamageIsAbsolute`设置伤害是绝对的，不会受到附魔、药水效果等影响。
- `setFireDamage`设置伤害由火焰造成，火焰保护附魔会起到作用。
- `setMagicDamage`设置伤害是由药水造成的。
- `setProjectile`设置伤害由弹射物造成，弹射物保护附魔会起到作用。

### 新的附魔属性

#### Enchantment类

 和附魔相关的类就是`Enchantment`类，打开`Enchantment`类，我们可以看到多个已经预设过的附魔种类。 

 `Enchantment`类的构造方法 

```java
protected Enchantment(int enchID, ResourceLocation enchName, int enchWeight, EnumEnchantmentType enchType)
```

我们解释一下这个构造方法的四个参数：

- `enchID`指的就是这个附魔的ID，我们看到原版已经定义了很多ID，当新建的ID重复时，游戏会报错。
- `enchName`指的就是这个附魔的名称，使用`ResourceLocation`的方式标记，比如时运就是`"minecraft:fortune"`，精准采集就是`"minecraft:silk_touch"`，这个名称和方块、物品的ID是类似的。
- `enchWeight`指的就是这个附魔的权重，和修复附魔需要的经验等级成负相关，和通过附魔台得到该种附魔的概率成正相关。
- `enchType`表示这种附魔是什么类型的，有武器、工具、弓等多种。

 我们注意到，`enchID`如果重复，游戏会报错，所以我们将这个ID写进配置，使得玩家可以修改它，以免和原版或者某些Mod重复。 

我们新建包`com.github.ustc_zzzz.fmltutor.enchantment`，并在其中新建一个文件`EnchantmentFireBurn.java`：

**`src/main/java/com/github/ustc_zzzz/fmltutor/enchantment/EnchantmentFireBurn.java`**

```java

public class EnchantmentFireBurn extends Enchantment
{
    public EnchantmentFireBurn()
    {
        super(ConfigLoader.enchantmentFireBurn, new ResourceLocation(FMLTutor.MODID + ":" + "fire_burn"), 1,
                EnumEnchantmentType.DIGGER);
        this.setName("fireBurn");
    }

    @Override
    public int getMinEnchantability(int enchantmentLevel)
    {
        return 15;
    }

    @Override
    public int getMaxEnchantability(int enchantmentLevel)
    {
        return super.getMinEnchantability(enchantmentLevel) + 50;
    }

    @Override
    public int getMaxLevel()
    {
        return 1;
    }

    @Override
    public boolean canApplyTogether(Enchantment ench)
    {
        return super.canApplyTogether(ench) && ench.effectId != silkTouch.effectId && ench.effectId != fortune.effectId;
    }

    @Override
    public boolean canApply(ItemStack stack)
    {
        return stack.getItem() == Items.shears ? true : super.canApply(stack);
    }
}
```

`setName`方法的作用和方块、物品等的`setUnlocalizedName`方法类似，我们修改一下语言文件：

**`src/main/resources/assets/fmltutor/lang/en_US.lang（部分）:`**

```
enchantment.fireBurn=Fire Burning
```

**`src/main/resources/assets/fmltutor/lang/zh_CN.lang（部分）:`**

```
enchantment.fireBurn=火焰灼烧
```

`getMinEnchantability`和`getMaxEnchantability`方法的作用就是获取可以获取到此附魔的最低等级和最高等级。这里被设置成了和精准采集相同。

`getMaxLevel`方法指的就是这个附魔的最大等级了。自然，这个附魔只应该有一个等级。

`canApplyTogether`方法表示的是这个附魔可否与其他附魔共存。这里设定为不能和精准采集和时运共存。

`canApply`方法表示的是这个附魔可以作用的物品。既然是一个作用于工具的附魔，自然作用对象是所有工具和剪刀。

然后我们在`com.github.ustc_zzzz.fmltutor.enchantment`包下新建`EnchantmentLoader.java`文件，完成对这个附魔属性的注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/enchantment/EnchantmentLoader.java`**

```
package com.github.ustc_zzzz.fmltutor.enchantment;

import com.github.ustc_zzzz.fmltutor.common.ConfigLoader;

import net.minecraft.enchantment.Enchantment;

public class EnchantmentLoader
{
    public static Enchantment fireBurn;

    public EnchantmentLoader()
    {
        try
        {
            fireBurn = new EnchantmentFireBurn();
            Enchantment.addToBookList(fireBurn);
        }
        catch (Exception e)
        {
            ConfigLoader.logger().error(
                    "Duplicate or illegal enchantment id: {}, the registry of class '{}' will be skipped. ",
                    ConfigLoader.enchantmentFireBurn, EnchantmentFireBurn.class.getName());
        }
    }
}
```

这里对该种附魔进行注册，如果ID重复，则输出错误信息。

`addToBookList`方法使得该附魔被注册，使其在附魔台上可以被注册到，在创造模式物品栏上也可以找到对应的附魔书。

下面是一张拥有此种附魔的钻石镐示例：

![fire_burn](https://fmltutor.ustc-zzzz.net/resources/fire_burn.png)

## 完善你的附魔

为了使我们的附魔可以产生作用，我们需要在特定的地方监听事件，以使这个附魔产生作用：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/EventLoader.java（部分）:`**

```java
    @SubscribeEvent
    public void onBlockHarvestDrops(BlockEvent.HarvestDropsEvent event)
    {
        if (!event.world.isRemote && event.harvester != null)
        {
            ItemStack itemStack = event.harvester.getHeldItem();
            if (EnchantmentHelper.getEnchantmentLevel(EnchantmentLoader.fireBurn.effectId, itemStack) > 0
                    && itemStack.getItem() != Items.shears)
            {
                for (int i = 0; i < event.drops.size(); ++i)
                {
                    ItemStack stack = event.drops.get(i);
                    ItemStack newStack = FurnaceRecipes.instance().getSmeltingResult(stack);
                    if (newStack != null)
                    {
                        newStack = newStack.copy();
                        newStack.stackSize = stack.stackSize;
                        event.drops.set(i, newStack);
                    }
                    else if (stack != null)
                    {
                        Block block = Block.getBlockFromItem(stack.getItem());
                        boolean b = (block == null);
                        if (!b && (block.isFlammable(event.world, event.pos, EnumFacing.DOWN)
                                || block.isFlammable(event.world, event.pos, EnumFacing.EAST)
                                || block.isFlammable(event.world, event.pos, EnumFacing.NORTH)
                                || block.isFlammable(event.world, event.pos, EnumFacing.SOUTH)
                                || block.isFlammable(event.world, event.pos, EnumFacing.UP)
                                || block.isFlammable(event.world, event.pos, EnumFacing.WEST)))
                        {
                            event.drops.remove(i);
                        }
                    }
                }
            }
        }
    }
```

我们监听了方块被挖掘后即将掉落物品的事件，在玩家手持存在“火焰灼烧”附魔的工具时，将其换成被灼烧过的物品掉落。

最后在`CommonProxy`中注册：

**`src/main/java/com/github/ustc_zzzz/fmltutor/common/CommonProxy.java（部分）:`**

```java
    public void init(FMLInitializationEvent event)
    {
        new CraftingLoader();
        new EnchantmentLoader();
        new EventLoader();
    }
```

### 制作新的药水

 [https://fmltutor.ustc-zzzz.net/2.3.3-%E6%96%B0%E7%9A%84%E8%8D%AF%E6%B0%B4%E6%95%88%E6%9E%9C.html](https://fmltutor.ustc-zzzz.net/2.3.3-新的药水效果.html) 





## 参考

 https://fmltutor.ustc-zzzz.net/ 