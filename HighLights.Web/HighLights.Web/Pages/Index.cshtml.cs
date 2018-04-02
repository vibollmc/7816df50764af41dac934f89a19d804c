using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.ViewModels;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace HighLights.Web.Pages
{
    public class IndexModel : PageModel
    {
        private readonly IMatchRepository _matchRepository;

        public IndexModel(IMatchRepository matchRepository)
        {
            _matchRepository = matchRepository;
        }

        [BindProperty]
        public IEnumerable<Match> Matches { get; set; }
        [BindProperty]
        public IList<string> Pages { get; set; }
        [BindProperty]
        public int CurrentPage { get; set; }

        public async Task<IActionResult> OnGetAsync(int? currentPage)
        {
            while (true)
            {
                if (currentPage == null) currentPage = 1;

                var totalPage = await _matchRepository.GetTotalPage();

                Matches = await _matchRepository.GetMatchs(currentPage.Value);

                if (currentPage != 1 && Matches == null)
                {
                    currentPage = 1;
                    continue;
                }
                if (currentPage == 1 && Matches == null) return NotFound();

                CurrentPage = currentPage.Value;

                Pages = new List<string>();
                if (totalPage > 15)
                {
                    Pages.Add("1");
                    if (CurrentPage > 5 && CurrentPage < totalPage - 5)
                    {
                        Pages.Add("...");
                        for (var i = CurrentPage - 4; i <= CurrentPage + 4; i++)
                        {
                            Pages.Add(i.ToString());
                        }
                        Pages.Add("...");
                    }
                    else if (CurrentPage > totalPage - 5)
                    {
                        Pages.Add("...");
                        for (var i = totalPage - 13; i <= totalPage - 1; i++)
                        {
                            Pages.Add(i.ToString());
                        }
                    }
                    else
                    {
                        for (var i = 2; i < 15; i++)
                        {
                            Pages.Add(i.ToString());
                        }
                        Pages.Add("...");
                    }
                    
                    Pages.Add(totalPage.ToString());
                }
                else
                {
                    for (var i = 1; i <= totalPage; i++)
                    {
                        Pages.Add(i.ToString());
                    }
                }

                return Page();
            }
        }
    }
}
