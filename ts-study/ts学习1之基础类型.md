﻿## ts学习笔记###  1. 基础类型+ 布尔值（boolean）+ 数字（number）+ 字符串（string）+ 枚举（enum）+ 任意值（any）+ 空值（void）+ Null和undefined+ never+ 数组（array）+ 元组（tuple）+ object+ 类型断言#### 1.1 boolean     let isBool: boolean = true;#### 1.2 number js和ts中，所有数字都是浮点数。    let decLiteral: number = 6; // 十进制    let hexLiteral: number = 0xf00d; // 十六进制    let binaryLiteral: number = 0b1010; // 二进制    let octalLiteral: number = 0o744; // 八进制#### 1.3 string+ 使用单引号 `"` 或者 双引号 `""` 来定义字符串变量                let name: string = "test";+ 使用模板字符串        let name: string = "test";        let age: number = 12;        let desc: string = `my name is ${ name }, my age is ${ age + 1 }`; // 解析为js的时候就会变成 + 拼接的方式#### 1.4 array+ 定义数组类型        let list: number[] = [1, 2, 3];+ 使用数组泛型：`Array<元素类型>`       let list: Array<number> = [1, 2, 3];#### 1.5 tuple 特殊的数组，元组类型允许表示一个已知元素类型和数量的数组。    let x: [number, string]; 当访问一个越界的元素，会使用联合类型替代    x[3] = "world"; //正确    x[4] = true; // 错误#### 1.6 enum所谓枚举是指将变量的值一一列举出来，变量只限于列举出来的值的范围内取值。 所谓枚举是指将变量的值一一列举出来，变量只限于列举出来的值的范围内取值。+ 定义枚举        enum Sex {boy, girl};+ 使用枚举        let sex: Sex = Sex.boy; // 使用元素获取值        let sex: string = sex[0] // 使用枚举值获取名称#### 1.7 any 有时候，我们会想要为那些在编程阶段还不清楚类型的变量指定一个类型。     let test: any = "test";     test = true; 或者数组中使用    let test: any[] = [1, true, "22"];    test[1] = "222";#### 1.8 void void 表示没有任何类型，可以在函数的返回值中使用    function test() : void{         console.log("test");    }声明一个void变量没有任何用，因为只能赋值为null和undefined    let test: void = undefined;#### 1.9 null和undefined+ 在ts中，null和undefined分别有对应的数据类型null和undefined。这两个类型是所有类型的子类型。  + 当指定了 `--strictNullChecks` 标记，null和undefined就只能赋给他们自己或者 void#### 1.10 never never类型表示的是那些永不存在的值的类型。+ never 和 void的区别 函数返回的时候，如果没有返回值，则会默认返回void。never指走不到返回这一步，可以是抛出异常或者死循环。#### 1.11 Object object指非原始类型，也就是除number，string，boolean，symbel，null或者undefined以外的类型。#### 1.12 类型断言 类型断言的语法有两种+ 尖括号形式        let someValue: any = "this is test";        let length: number = (<string>someValue).length;+ as 形式        let  someValue: any = "this is test";        let length: number = (someValue as string).length; 