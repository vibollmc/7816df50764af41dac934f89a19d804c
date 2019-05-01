using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.ViewModels
{
    public class PagingResult
    {
        public int CurrentPage { get; set; }
        public int TotalPage { get; set; }
        public IEnumerable<Match> Matches { get; set; }
        public string PageUrl { get; set; }
        public string PageTitle { get; set; }

        public List<string> PagingGenerate
        {
            get
            {
                if (TotalPage < 11)
                {
                    return Enumerable.Range(1, CurrentPage).Select(x => x.ToString()).ToList();
                }
                else
                {
                    if (CurrentPage <= 6)
                    {
                        var result = Enumerable.Range(1, 9).Select(x => x.ToString()).ToList();
                        result.Add("...");
                        result.Add(TotalPage.ToString());

                        return result;
                    }
                    else if (CurrentPage >= TotalPage - 6)
                    {
                        var result = Enumerable.Range(TotalPage - 9, 9).Select(x => x.ToString()).ToList();

                        result.Insert(0, "...");
                        result.Insert(0, "1");

                        return result;
                    }
                    else
                    {
                        var result = Enumerable.Range(CurrentPage - 4, 9).Select(x => x.ToString()).ToList();

                        result.Insert(0, "...");
                        result.Insert(0, "1");

                        result.Add("...");
                        result.Add(TotalPage.ToString());

                        return result;
                    }
                }
                
            }
        }
    }
}
