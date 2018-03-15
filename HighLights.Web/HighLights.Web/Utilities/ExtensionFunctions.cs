using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Threading.Tasks;

namespace HighLights.Web.Utilities
{
    public static class ExtensionFunctions
    {
        /// <summary>
        /// Convert string to datetime?
        /// </summary>
        /// <param name="input">string input</param>
        /// <param name="format">string format</param>
        /// <returns>Datetime</returns>
        public static DateTime? ToDateTime(this string input, string format)
        {
            if (DateTime.TryParseExact(input, format, null, DateTimeStyles.None, out var date)) return date;

            return null;
        }
        /// <summary>
        /// Convert string with format 'dd MMMM yyyy' to datetime?
        /// </summary>
        /// <param name="input">string input</param>
        /// <returns>datetime value</returns>
        public static DateTime? ToDate(this string input)
        {
            return input.ToDateTime("dd MMMM yyyy");
        }

        /// <summary>
        /// Convert string to int?
        /// </summary>
        /// <param name="input"></param>
        /// <returns></returns>
        public static int? ToInt(this string input)
        {
            if (int.TryParse(input, out var oint)) return oint;

            return null;
        }
    }
}
