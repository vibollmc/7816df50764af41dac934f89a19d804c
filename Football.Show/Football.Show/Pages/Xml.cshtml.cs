using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.Extensions.Configuration;

namespace Football.Show.Pages
{
    public class XmlModel : PageModel
    {
        private readonly IMatchRepository _matchRepository;
        private readonly ITagRepository _tagRepository;
        private readonly ICategoryRepository _categoryRepository;
        private readonly string _domain;

        public IList<ViewModels.XmlModel> Links;

        public XmlModel(IMatchRepository matchRepository, 
            ITagRepository tagRepository, 
            ICategoryRepository categoryRepository, 
            IConfiguration configuration)
        {
            _matchRepository = matchRepository;
            _tagRepository = tagRepository;
            _categoryRepository = categoryRepository;
            _domain = configuration["DomainHosting"];
        }

        public async Task OnGetAsync(string type)
        {
            switch (type.ToLower())
            {
                case "league":
                    var categories = await _categoryRepository.GetCategories();
                    Links = categories.Select(x => new ViewModels.XmlModel
                    {
                        Loc = $"http://{_domain}/league/{x.Slug}",
                        LastMod = x.UpdatedAt.HasValue ? x.UpdatedAt.Value : x.CreatedAt.Value,
                        Priority = 0.8,
                        ChangeFreq = "daily"
                    }).ToList();
                    break;
                case "tag":
                    var tags = await _tagRepository.GetTags();
                    Links = tags.Select(x => new ViewModels.XmlModel
                    {
                        Loc = $"http://{_domain}/tag/{x.Slug}",
                        LastMod = x.UpdatedAt.HasValue ? x.UpdatedAt.Value : x.CreatedAt.Value,
                        Priority = 0.8,
                        ChangeFreq = "daily"
                    }).ToList();
                    break;
                case "match":
                    Links = (await _matchRepository.GetAllMatchLinks()).ToList();
                    break;
            }

            Links.Insert(0, new ViewModels.XmlModel
            {
                ChangeFreq = "always",
                Priority = 1,
                LastMod = DateTime.UtcNow,
                Loc = $"http://{_domain}"
            });
        }
    }
}