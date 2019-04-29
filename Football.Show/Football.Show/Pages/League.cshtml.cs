using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.ViewModels;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace Football.Show.Pages
{
    public class LeagueModel : PageModel
    {
        private readonly ICategoryRepository _categoryRepository;
        private readonly IMatchRepository _matchRepository;

        public LeagueModel(ICategoryRepository categoryRepository, IMatchRepository matchRepository)
        {
            _categoryRepository = categoryRepository;
            _matchRepository = matchRepository;
        }

        public PagingResult PagingResult;
        public async Task OnGetAsync(string slug, string currentPage)
        {
            var category = await _categoryRepository.GetCategory(slug);

            if (category == null) Redirect("/");

            var page = 1;

            if (!string.IsNullOrWhiteSpace(currentPage))
            {
                int.TryParse(currentPage.ToLower().Replace("page-", ""), out page);
            }

            if (page < 1) page = 1;

            PagingResult = await _matchRepository.GetMatchsByCategory(slug, page);
            PagingResult.PageUrl = $"/league/{slug}";
            PagingResult.PageTitle = category.Name;
        }
    }
}