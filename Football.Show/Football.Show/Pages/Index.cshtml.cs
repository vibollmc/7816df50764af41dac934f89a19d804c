﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.ViewModels;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace Football.Show.Pages
{
    public class IndexModel : PageModel
    {
        private readonly IMatchRepository _matchRepository;
        public IndexModel(IMatchRepository matchRepository)
        {
            _matchRepository = matchRepository;
        }

        public PagingResult PagingResult;
        public async Task OnGetAsync(string currentPage)
        {
            var page = 1;

            if (!string.IsNullOrWhiteSpace(currentPage))
            {
                int.TryParse(currentPage.ToLower().Replace("page-", ""), out page);
            }

            if (page < 1) page = 1;

            PagingResult = await _matchRepository.GetMatchs(page);
            PagingResult.PageUrl = "";
            PagingResult.PageTitle = "Latest Highlights and Full Matches";
        }
    }
}
