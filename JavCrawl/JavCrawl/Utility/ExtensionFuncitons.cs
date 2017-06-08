using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility
{
    public static class ExtensionFuncitons
    {
        public static string ToTitleCase(this string str)
        {
            var arr = str.ToLower().Split(' ');
            var results = string.Empty;
            foreach(var s in arr)
            {
                if (string.IsNullOrWhiteSpace(s)) continue;

                results += s.First().ToString().ToUpper() + s.Substring(1) + " ";
            }

            return results.Trim();
        }
        
    }
}
