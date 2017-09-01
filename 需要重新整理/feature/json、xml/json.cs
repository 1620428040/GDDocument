using System.Web.Script.Serialization;


string[] strs = { "ab", "bc", "cd" };
JavaScriptSerializer serializer = new JavaScriptSerializer();
string json = serializer.Serialize(strs);
string[] strss = serializer.Deserialize<string[]>(json);

//将json数据转化为对象时，需要明确的类型