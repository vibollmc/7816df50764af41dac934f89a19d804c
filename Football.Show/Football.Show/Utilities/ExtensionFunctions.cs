using Football.Show.Entities.Enum;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;

namespace Football.Show.Utilities
{
    public static class ExtensionFunctions
    {
        /// <summary>
        /// Paging
        /// </summary>
        /// <typeparam name="TSource"></typeparam>
        /// <param name="source"></param>
        /// <param name="page"></param>
        /// <param name="pageSize"></param>
        /// <returns></returns>
        public static IQueryable<TSource> Page<TSource>(this IQueryable<TSource> source, int page, int pageSize)
        {
            return source.Skip((page - 1) * pageSize).Take(pageSize);
        }

        /// <summary>
        /// Paging
        /// </summary>
        /// <typeparam name="TSource"></typeparam>
        /// <param name="source"></param>
        /// <param name="page"></param>
        /// <param name="pageSize"></param>
        /// <returns></returns>
        public static IEnumerable<TSource> Page<TSource>(this IEnumerable<TSource> source, int page, int pageSize)
        {
            return source.Skip((page - 1) * pageSize).Take(pageSize);
        }

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
            return input.ToDateTime("d MMMM yyyy");
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

        public static string ToTitle(this ClipType clipType)
        {
            switch(clipType)
            {
                case ClipType.ExtraTimeOrPen:
                    return "Extra Time/Pens if any";
                case ClipType.FirstHalf:
                    return "1st Half";
                case ClipType.HalfTime:
                    return "Half Time";
                case ClipType.Highlights:
                    return "Hightlights";
                case ClipType.PostMatch:
                    return "Post match";
                case ClipType.PreMatch:
                    return "Pre-match";
                case ClipType.SecondHalf:
                    return "2nd Half";
                default:
                    return null;
            }
        }

        public static ViewModels.Match ToViewModel(this Entities.Match match)
        {
            if (match == null) return null;

            return new ViewModels.Match
            {
                MatchDate = match.MatchDate,
                Slug = match.Slug,
                Title = match.Title,
                ImageUrl = $"{match.ImageServer.ServerUrl}/{match.ImageName}"
            };
        }
    }
}
