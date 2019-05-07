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
    public class TagModel : PageModel
    {
        private readonly ITagRepository _tagRepository;
        private readonly IMatchRepository _matchRepository;
        public TagModel(ITagRepository tagRepository, IMatchRepository matchRepository)
        {
            _tagRepository = tagRepository;
            _matchRepository = matchRepository;
        }

        public PagingResult PagingResult;
        public async Task OnGetAsync(string slug, string currentPage)
        {
            var tags = await _tagRepository.GetTags(slug);

            if (tags == null || !tags.Any()) Redirect("/");

            var page = 1;

            if (!string.IsNullOrWhiteSpace(currentPage))
            {
                int.TryParse(currentPage.ToLower().Replace("page-", ""), out page);
            }

            if (page < 1) page = 1;

            PagingResult = await _matchRepository.GetMatchsByTag(page, tags.Select(x => x.Id.Value).ToArray());
            PagingResult.PageUrl = $"/tag/{slug}" ;
            PagingResult.PageTitle = $"{tags.First().Name} - Latest Highlights and Full Matches";
        }
    }
}