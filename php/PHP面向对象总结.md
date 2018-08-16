## PHP5面向对象总结

### 写在前面

 本文是我通过对手册的阅读后总结而来的，当然也参考了一些大神的博客。PHP开发很简单，手册中的细节不记得也能进行开发，有Google和百度就可以。但是对一些PHP细节的学习和总结，能帮助我们更好的学习。

### PHP底层实现

 对PHP底层架构的实现能帮助我们更好的理解PHP5对象。

#### 变量的结构体定义如下：

	typedef union _zvalue_value {
	    long lval;     /* long value */
	    double dval;    /* double value */
	    struct {
	       char *val;
	       int len;
	    } str;
	    HashTable *ht;    /* hash table value */
	    zend_object_value obj; //存储的对象
	    zend_ast *ast;
	} zvalue_value;

 如果, 一个变量是对象, 那么 `zvalue_value`中的obj, 就指向一个`zend_object_value`的实例。

#### 对象实例的结构体定义如下：

	typedef struct_zend_object
	{
	    zend_class_entry *ce;   //这里就是类的入口
	    HashTable *properties;  //属性组成的HashTable
	    HashTable *guards;  /*protect from __get/__set...recursion */
	} zend_object;

 一个 `properties` 中存储的是普通变量，对变量进行权限区分通过以下方式实现

1. `public`  属性名
2. `private` \0类名\0属性名
3. `protected` \0*\0属性名

#### 类结构体的定义如下：

	struct _zend_class_entry {
		char type;
		char *name;	/* 类名 */
		zend_uint name_length;	/* 类名字符串长度 */
		struct _zend_class_entry *parent; /* 父类 */
		int refcount; /* 引用计数 */
		zend_bool constants_updated;
		zend_uint ce_flags;	/* 类的访问控制 */
	 
		HashTable function_table;	/*	类的成员函数 */
		HashTable default_properties;	/*	类的默认属性 */
		HashTable properties_info;	/*	类的属性信息 如访问控制等 */
		HashTable default_static_members;/*	静态成员列表 */
		HashTable *static_members;
		HashTable constants_table;	/* 常量列表 */
		const struct _zend_function_entry *builtin_functions;
	 
		union _zend_function *constructor;	/*	构造函数*/
		union _zend_function *destructor;	/*	析构函数*/
		union _zend_function *clone;		/*	克隆方法*/
	 
		/*	魔术方法 */
		union _zend_function *__get;
		union _zend_function *__set;
		union _zend_function *__unset;
		union _zend_function *__isset;
		union _zend_function *__call;
		union _zend_function *__callstatic;
		union _zend_function *__tostring;
		union _zend_function *serialize_func;
		union _zend_function *unserialize_func;
	 
		zend_class_iterator_funcs iterator_funcs;
	 
		/* handlers */
		zend_object_value (*create_object)(zend_class_entry *class_type TSRMLS_DC);
		zend_object_iterator *(*get_iterator)(zend_class_entry *ce, zval *object, int by_ref TSRMLS_DC);
		int (*interface_gets_implemented)(zend_class_entry *iface, zend_class_entry *class_type TSRMLS_DC); /* a class implements this interface */
		union _zend_function *(*get_static_method)(zend_class_entry *ce, char* method, int method_len TSRMLS_DC);
	 
		/* serializer callbacks */
		int (*serialize)(zval *object, unsigned char **buffer, zend_uint *buf_len, zend_serialize_data *data TSRMLS_DC);
		int (*unserialize)(zval **object, zend_class_entry *ce, const unsigned char *buf, zend_uint buf_len, zend_unserialize_data *data TSRMLS_DC);
	 
		zend_class_entry **interfaces;	/*	类实现的接口 */
		zend_uint num_interfaces;	/*	类实现的接口数 */
	 
		/*	类所在文件信息 */
		char *filename;
		zend_uint line_start;
		zend_uint line_end;
		char *doc_comment;
		zend_uint doc_comment_len;
	 
		struct _zend_module_entry *module;
	};
 
#### 总结

 由此可见，变量中存储着对象结构体的入口，对象中存储着类的入口以及普通属性，类中存储着关于 `方法集合、静态属性、类常量`等常见的HashTable。